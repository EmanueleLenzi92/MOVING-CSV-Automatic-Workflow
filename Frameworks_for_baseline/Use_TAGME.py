import requests
import os
import json
import csv
import time

def aggiorna_file_json(nuovi_dati, percorso_file):
    """
    Create and update JSON files with keywords retrieved by the TAGME API.
    """
    if os.path.exists(percorso_file):
        with open(percorso_file, 'r', encoding='utf-8') as file:
            try:
                dati_esistenti = json.load(file)
            except json.JSONDecodeError:
                dati_esistenti = []
    else:
        dati_esistenti = []

    dati_esistenti.append(nuovi_dati)
    os.makedirs(os.path.dirname(percorso_file), exist_ok=True)

    with open(percorso_file, 'w', encoding='utf-8') as file:
        json.dump(dati_esistenti, file, ensure_ascii=False, indent=4)


def get_wikidata_entity_from_wikipedia_title(language, title):
    """
    Get Wikidata ID from a Wikipedia title using the Wikipedia API.
    """
    url = f"https://{language}.wikipedia.org/w/api.php"
    params = {
        "action": "query",
        "prop": "pageprops",
        "titles": title,
        "format": "json",
        "redirects": 1
    }
    headers = {
        "User-Agent": "PythonScript/1.0 (mailto:youremail@example.com)"
    }

    try:
        response = requests.get(url, params=params, headers=headers, timeout=10)
    except Exception as e:
        print(f"[Wikipedia API] Errore di rete per {title}: {e}")
        return None

    if response.status_code != 200:
        print(f"[Wikipedia API] Errore {response.status_code} per {title}")
        return None

    try:
        data = response.json()
    except ValueError:
        print(f"[Wikipedia API] Risposta non JSON per {title}")
        return None

    pages = data.get("query", {}).get("pages", {})
    if pages:
        page = next(iter(pages.values()))
        if "pageprops" in page and "wikibase_item" in page["pageprops"]:
            return page["pageprops"]["wikibase_item"]
    return None


# === MAIN ===

directory = "../Moving_Dataset_Selected_Narratives/dataset_keywords/"
output_dir = "baseline_data_output"
os.makedirs(output_dir, exist_ok=True)

TAGME_URL = "https://tagme.d4science.org/tagme/tag"
TAGME_TOKEN = "a470fac7-cba8-498e-8a3f-d68baa3515da-843339462"
HEADERS = {"User-Agent": "PythonScript/1.0 (mailto:youremail@example.com)"}

error_log_path = "error_log.txt"

for filename in os.listdir(directory):
    if filename.endswith(".csv"):
        filepath = os.path.join(directory, filename)
        print(f"\n📄 Elaboro file: {filename}")

        with open(filepath, newline='', encoding='utf-8') as csvfile:
            csvreader = csv.reader(csvfile)
            next(csvreader)  # salta intestazione

            for row in csvreader:
                if len(row) > 1:
                    sen = row[1]
                    new_item = {"text": sen, "entities": []}
                    params = {
                        "text": sen,
                        "lang": "en",
                        "gcube-token": TAGME_TOKEN
                    }

                    # === Retry mechanism per TAGME ===
                    max_retries = 3
                    attempt = 0
                    success = False

                    while attempt < max_retries and not success:
                        attempt += 1
                        try:
                            response1 = requests.post(TAGME_URL, data=params, headers=HEADERS, timeout=15)
                            print(f"[TAGME] Tentativo {attempt} - Status: {response1.status_code}")

                            if response1.status_code == 200:
                                success = True
                            else:
                                print(f"[TAGME] Errore {response1.status_code}: {response1.text[:200]}")
                                time.sleep(5)
                        except Exception as e:
                            print(f"[TAGME] Errore di rete (tentativo {attempt}): {e}")
                            time.sleep(10)

                    if not success:
                        print(f"[TAGME] Fallito dopo {max_retries} tentativi per testo: {sen[:60]}...")
                        with open(error_log_path, "a", encoding="utf-8") as log:
                            log.write(f"TAGME fallito per file {filename}, testo: {sen[:80]}...\n")
                        continue

                    # === Parsing JSON ===
                    try:
                        data = response1.json()
                    except ValueError:
                        print(f"[TAGME] Risposta non JSON per testo: {sen[:60]}...")
                        with open(error_log_path, "a", encoding="utf-8") as log:
                            log.write(f"TAGME non JSON per file {filename}, testo: {sen[:80]}...\n")
                        time.sleep(5)
                        continue

                    # === Estrai le entità ===
                    annotations = data.get("annotations", [])
                    for annotation in annotations:
                        title = annotation.get("title")
                        if not title:
                            continue

                        wikidata_entity = get_wikidata_entity_from_wikipedia_title("en", title)
                        entity = {
                            "originalKey": annotation.get("spot"),
                            "original_value": title,
                            "Wikidata_ID": wikidata_entity,
                            "rho": annotation.get("rho")
                        }
                        new_item["entities"].append(entity)

                        # piccola pausa tra le chiamate Wikipedia
                        time.sleep(1.5)

                    # === Salva i dati ===
                    json_path = os.path.join(output_dir, f"{filename}.json")
                    aggiorna_file_json(new_item, json_path)
                    print(f"💾 Salvato: {json_path}")

                    # pausa tra richieste successive a TAGME
                    time.sleep(5)
