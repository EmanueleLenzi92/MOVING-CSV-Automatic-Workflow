import csv
import io
import os
import re
import sys
import difflib


# -------------------------
# CONFIG
# -------------------------
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
INPUT_DIR = os.path.join(BASE_DIR, "input")
OUTPUT_DIR = os.path.join(BASE_DIR, "output")
MAPPING_CSV = os.path.join(BASE_DIR, "mappingtable.csv")

SKIP_EMPTY_FIELDS = True
FUZZY_THRESHOLD = 0.90
PRINT_FUZZY_MATCHES = False


def ensure_io_dirs() -> None:
    os.makedirs(INPUT_DIR, exist_ok=True)
    os.makedirs(OUTPUT_DIR, exist_ok=True)


def resolve_default_input_path() -> str:
    ensure_io_dirs()
    candidates = []
    for name in sorted(os.listdir(INPUT_DIR)):
        path = os.path.join(INPUT_DIR, name)
        if os.path.isfile(path) and name.lower().endswith((".txt", ".csv")):
            candidates.append(path)
    if not candidates:
        raise FileNotFoundError(f"No .txt or .csv input files found in {INPUT_DIR}")
    candidates.sort(key=lambda path: (0 if path.lower().endswith(".txt") else 1, os.path.basename(path).lower()))
    return candidates[0]

# -------------------------
# UTILS (shared)
# -------------------------
def norm_key(s: str) -> str:
    if s is None:
        return ""
    x = str(s).replace("\ufeff", "").replace("\u00a0", " ")
    x = x.strip().lower()
    x = re.sub(r"\bnuts\s*([23])\b", r"nuts\1", x)
    x = re.sub(r"[(),]", " ", x)
    x = x.replace("/", " ").replace("-", " ")
    x = re.sub(r"\s+", " ", x).strip()
    return x


def norm_key_aggressive(s: str) -> str:
    x = norm_key(s)
    if not x:
        return ""
    stop = {"per", "of", "total", "year", "eur", "million"}
    tokens = [t for t in x.split() if t not in stop]
    y = " ".join(tokens).strip()
    y = re.sub(r"\s+", " ", y)
    return y


def clean_text(s: str) -> str:
    if s is None:
        return ""
    x = str(s).replace('"', "'")
    x = x.replace("\ufeff", "").replace("\u00a0", " ")
    x = x.strip()
    x = re.sub(r"\s+", " ", x)
    if x.upper() == "N/A":
        return ""
    return x


def parse_bool(s: str) -> bool:
    x = clean_text(s).lower()
    return x in {"true", "1", "yes", "y"}


def build_sentence(prefix: str, content: str, suffix: str) -> str:
    c = clean_text(content)
    if not c:
        return ""

    if c.lower() in {"yes", "y"}:
        c = "are"
    elif c.lower() in {"no", "n"}:
        c = "are not"

    p = (prefix or "").strip()
    suf = (suffix or "").strip()

    s = f"{p} {c} {suf}".strip()
    if s and not s.endswith("."):
        s += "."
    return s


def csv_cell(s: str) -> str:
    x = (s or "").replace("\r", " ").replace("\n", " ").strip()
    x = x.replace('"', '""')
    return f'"{x}"'


def build_csv_text(rows: list[list[str]]) -> str:
    buffer = io.StringIO(newline="")
    writer = csv.writer(buffer, lineterminator="\n")
    writer.writerows(rows)
    return buffer.getvalue()


def is_quantitative_event(label: str) -> bool:
    keywords = ["income", "gross value", "employment", "gva", "population", "density"]
    label = (label or "").lower()
    return any(k in label for k in keywords)


# -------------------------
# MAPPING (CSV pipeline)
# -------------------------
def load_mapping(mapping_path: str):
    mapping_rows = []
    mapping_by_dbkey = {}
    event_order = []
    seen_events = set()

    with open(mapping_path, newline="", encoding="utf-8", errors="replace") as f:
        reader = csv.reader(f)
        rows = list(reader)

    if not rows:
        raise RuntimeError("mappingtable.csv is empty")

    for i, r in enumerate(rows):
        if i == 0:
            continue
        if len(r) < 5:
            continue

        db_label_raw = r[0]
        event_label = clean_text(r[1])
        prefix = r[2] if len(r) > 2 else ""
        suffix = r[3] if len(r) > 3 else ""
        is_title = parse_bool(r[4])

        db_key = norm_key(db_label_raw)
        db_key_aggr = norm_key_aggressive(db_label_raw)

        if not db_key or not event_label:
            continue

        mapping_rows.append((db_label_raw, db_key, db_key_aggr, event_label, prefix, suffix, is_title))
        mapping_by_dbkey[db_key] = (event_label, prefix, suffix, is_title)

        if event_label not in seen_events:
            seen_events.add(event_label)
            event_order.append(event_label)

    return mapping_rows, mapping_by_dbkey, event_order


def build_header_resolution(headers_raw, mapping_rows, mapping_by_dbkey):
    resolved = {}

    # exact
    for h_raw in headers_raw:
        h_key = norm_key(h_raw)
        m = mapping_by_dbkey.get(h_key)
        if m:
            resolved[h_key] = m

    # fuzzy
    mapping_aggr_keys = [mr[2] for mr in mapping_rows]

    for h_raw in headers_raw:
        h_key = norm_key(h_raw)
        if not h_key or h_key in resolved:
            continue

        h_aggr = norm_key_aggressive(h_raw)
        if not h_aggr:
            continue

        best = difflib.get_close_matches(h_aggr, mapping_aggr_keys, n=1, cutoff=FUZZY_THRESHOLD)
        if not best:
            continue

        best_aggr = best[0]
        idx = mapping_aggr_keys.index(best_aggr)
        db_label_raw, db_key, db_key_aggr, event_label, prefix, suffix, is_title = mapping_rows[idx]
        resolved[h_key] = (event_label, prefix, suffix, is_title)

        if PRINT_FUZZY_MATCHES:
            sim = difflib.SequenceMatcher(None, h_aggr, best_aggr).ratio()
            print(f"[FUZZY] '{h_raw}' -> '{db_label_raw}' (sim={sim:.2f})")

    return resolved


# -------------------------
# SERIALIZE
# -------------------------
def serialize_story_csv(events: dict, event_order: list) -> str:
    rows = [["title", "description"]]

    for event_label in event_order:
        data = events[event_label]
        title = clean_text(data.get("title", "")) or event_label
        desc = " ".join([clean_text(x) for x in data.get("desc", []) if clean_text(x)]).strip()

        rows.append([title, desc])

    return build_csv_text(rows)


def serialize_story_txt(paragraphs: list[str]) -> str:
    rows = [["title", "description"]]
    for i, paragraph in enumerate(paragraphs, start=1):
        title = f"event-{i}"
        rows.append([title, paragraph])
    return build_csv_text(rows)


# -------------------------
# PROCESSORS
# -------------------------
def process_csv(dataset_csv_path: str, mapping_csv_path: str = MAPPING_CSV, output_dir: str = OUTPUT_DIR):
    ensure_io_dirs()
    mapping_rows, mapping_by_dbkey, event_order = load_mapping(mapping_csv_path)
    os.makedirs(output_dir, exist_ok=True)

    with open(dataset_csv_path, newline="", encoding="utf-8", errors="replace") as f:
        reader = csv.reader(f)
        all_rows = list(reader)

    if not all_rows:
        print("Dataset is empty.")
        return

    headers_raw = all_rows[0]
    headers_keys = [norm_key(h) for h in headers_raw]
    resolved_header_map = build_header_resolution(headers_raw, mapping_rows, mapping_by_dbkey)

    mapped_dataset_cols = sum(1 for k in headers_keys if k and k in resolved_header_map)
    print(f"Dataset columns mapped (exact+fuzzy): {mapped_dataset_cols} of {len(headers_keys)}")

    for row_index in range(1, len(all_rows)):
        row = all_rows[row_index]

        
        country = clean_text(row[0]) if row else ""

        events = {ev: {"title": "", "desc": []} for ev in event_order}

        ncols = min(len(headers_keys), len(row))
        for col in range(ncols):
            h_key = headers_keys[col]
            if not h_key:
                continue

            m = resolved_header_map.get(h_key)
            if not m:
                continue

            field = clean_text(row[col])
            if SKIP_EMPTY_FIELDS and not field:
                continue

            event_label, prefix, suffix, is_title = m
            sentence = build_sentence(prefix, field, suffix)
            if not sentence:
                continue

            if is_title:
                if not events[event_label]["title"]:
                    events[event_label]["title"] = sentence
            else:
                events[event_label]["desc"].append(sentence)

        story_csv = serialize_story_csv(events, event_order)
        out_path = os.path.join(output_dir, f"{row_index}.csv")
        with open(out_path, "w", encoding="utf-8", newline="") as out:
            out.write(story_csv)

        print(f"Story {row_index} saved -> {out_path}")

    return output_dir





# -------------------------
# ENTRYPOINT
# -------------------------
def run(input_path: str | None = None):
    """
    input_path è SEMPRE una stringa (percorso file).
    Se finisce con .csv -> genera stories con colonne title/description.
    Se finisce con .txt -> usa Ollama e genera CSV title/description.
    """
    path = input_path.strip() if isinstance(input_path, str) and input_path.strip() else resolve_default_input_path()
    if not os.path.isfile(path):
        raise FileNotFoundError(f"File not found: {path}")

    lower = path.lower()
    if lower.endswith(".csv"):
        return process_csv(path, mapping_csv_path=MAPPING_CSV, output_dir=OUTPUT_DIR)
    else:
        raise ValueError("Input path must end with .csv")


if __name__ == "__main__":
    input_path = sys.argv[1] if len(sys.argv) > 1 else None
    result = run(input_path)
    if result:
        print(result)
