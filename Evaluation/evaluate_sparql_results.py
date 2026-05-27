import csv
import json
import re
import sys
from pathlib import Path


VALUE_CHAIN_ID_PATTERN = re.compile(r"(VC_[^\s\"'/,]+)")

###Select the query that you want to evaluate using the csv files (i.e., the results od the SPARQL queries in csv format) in the "CSV_NAME" variable
###Select the folder where the results of the queries are placed in the main function ("SPARQL_Queries" or "SPARQL_Queries_noEvents")

CSV_NAME = "Q1.csv"
#CSV_NAME = "Q2.csv"
#CSV_NAME = "Q3.csv"
#CSV_NAME = "Q4.csv"

EVALUATION_DIR = Path("Data")


def extract_value_chain_id(value: str) -> str:
    match = VALUE_CHAIN_ID_PATTERN.search(value)
    if not match:
        raise ValueError(f"Impossibile ricavare un id value chain da: {value}")
    return match.group(1)


def load_predicted_ids(csv_path: Path) -> set[str]:
    predicted_ids: set[str] = set()
    with csv_path.open("r", encoding="utf-8-sig", newline="") as handle:
        reader = csv.DictReader(handle)
        if "narrative" not in (reader.fieldnames or []):
            raise ValueError(f"Colonna 'narrative' non trovata in {csv_path}")

        for row in reader:
            narrative_value = (row.get("narrative") or "").strip()
            if not narrative_value:
                continue
            predicted_ids.add(extract_value_chain_id(narrative_value))
    return predicted_ids


def resolve_gold_json(query_name: str, gold_dir: Path) -> Path:
    candidate_dir = gold_dir / query_name
    if not candidate_dir.exists():
        raise FileNotFoundError(f"Cartella gold standard non trovata: {candidate_dir}")

    candidates = [candidate_dir / "gold.json", candidate_dir / "a.json"]
    for candidate in candidates:
        if candidate.exists():
            return candidate

    raise FileNotFoundError(
        f"Nessun gold standard JSON trovato in {candidate_dir}. Attesi gold.json o a.json"
    )


def load_gold_ids(gold_json_path: Path) -> set[str]:
    data = json.loads(gold_json_path.read_text(encoding="utf-8-sig"))
    gold_ids = set()
    for item in data:
        narrative_id = item.get("id")
        if not narrative_id:
            continue
        gold_ids.add(extract_value_chain_id(str(narrative_id)))
    return gold_ids


def safe_divide(numerator: int, denominator: int) -> float:
    if denominator == 0:
        return 0.0
    return numerator / denominator


def main() -> int:
    evaluation_dir = EVALUATION_DIR
    
    #results_dir = evaluation_dir / "SPARQL_Queries_noEvents"
    results_dir = evaluation_dir / "SPARQL_Queries"
    gold_dir = evaluation_dir / "Goldstandard"

    csv_path = results_dir / CSV_NAME
    if not csv_path.exists():
        print(f"CSV non trovato: {csv_path}", file=sys.stderr)
        return 1

    query_name = csv_path.stem

    try:
        gold_json_path = resolve_gold_json(query_name, gold_dir)
        predicted_ids = load_predicted_ids(csv_path)
        gold_ids = load_gold_ids(gold_json_path)
    except Exception as exc:
        print(str(exc), file=sys.stderr)
        return 1

    true_positives = predicted_ids & gold_ids
    false_positives = predicted_ids - gold_ids
    false_negatives = gold_ids - predicted_ids

    precision = safe_divide(len(true_positives), len(predicted_ids))
    recall = safe_divide(len(true_positives), len(gold_ids))
    f1 = safe_divide(2 * precision * recall, precision + recall) if (precision + recall) else 0.0

    print(f"Query: {query_name}")
    print(f"CSV: {csv_path}")
    print(f"Gold standard: {gold_json_path}")
    print(f"Predicted: {len(predicted_ids)}")
    print(f"Gold: {len(gold_ids)}")
    print(f"TP: {len(true_positives)}")
    print(f"FP: {len(false_positives)}")
    print(f"FN: {len(false_negatives)}")
    print(f"Precision: {precision:.4f}")
    print(f"Recall: {recall:.4f}")
    print(f"F1: {f1:.4f}")

    return 0


if __name__ == "__main__":
    raise SystemExit(main())
