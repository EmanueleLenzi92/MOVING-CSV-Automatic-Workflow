# Keywords and Named Entities Linking with Wikidata Using Small LLMs

This repository contains an experiment on extracting keywords and named entities from textual data with small open-source LLMs, then linking them to Wikipedia and Wikidata.

The final goal is to support automatic knowledge graph population by using Wikidata as the reference knowledge base.

## Overview

The pipeline is:

1. Extract keywords or named entities from text with small open-source LLMs.
2. Ask the models to return the corresponding Wikipedia titles.
3. Use Wikipedia APIs to retrieve the matching Wikipedia page.
4. Retrieve the related Wikidata QID.
5. Evaluate the results against manually created gold standards.

## Dependencies

To run the LLMs, you have to install Ollama <https://ollama.com/> and download the models used in the experiments.

To run ReLik (used to extract Named entities and link them to WIkidata), you have to install ReLik <https://github.com/SapienzaNLP/relik> and Kilt <https://github.com/facebookresearch/KILT>

## Data Source

The textual data used in the experiment comes from the European Horizon 2020 **MOVING** project.

MOVING collected data about **454 mountain value chains** across **16 European countries**.

The original dataset corresponds to:

- 454 CSV narratives (<https://github.com/EmanueleLenzi92/MOVING-CSV-Automatic-Workflow/tree/master/Workflow-module-1/output>)
- 11 textual events for each narrative
- 4,994 textual events in total

## Sample Selection

Two datasets were created for the experiment:

- one for **named entities**
- one for **keywords**

Both datasets are statistically significant subsets of the 454 narratives. The sample size was estimated with the standard sample size formula with **finite population correction**, using the full set of **4,994 textual events** as population size `N` and standard default parameters for confidence and risk minimization.

At the end, **30 narratives** were randomly selected.

These selected data are stored in:

- `Moving_Dataset_Selected_Narratives/`

## Gold Standards

Two gold standards were manually created:

- one for **named entities**, with the entities identified in each narrative and event, together with the corresponding Wikidata QIDs
- one for **keywords**, with the keywords identified in each narrative and event, together with the corresponding Wikidata QIDs

They are stored in:

- `Gold_Standards/goldStandard_Named_Entites/`
- `Gold_Standards/gold_standard_keywords/`

## LLM Extraction and Wikidata Linking

Main scripts:

- `ollama_Named_Entites.py`: extracts named entities with small LLMs through Ollama
- `ollama_keywords.py`: extracts keywords with small LLMs through Ollama
- `getWidataIdUsingWikipediaAPIs.py`: uses Wikipedia APIs to retrieve the Wikipedia entity and then the Wikidata QID

The LLMs are prompted to return:

- the extracted named entity or keyword
- the corresponding Wikipedia title

Then the Wikipedia API layer is used to resolve the page and recover the final Wikidata QID.

## Evaluation

`evaluation.py` evaluates LLM outputs against the gold standards.

The evaluation is based on mention matching plus Wikidata QID matching.

For each gold item and predicted item:

1. Gold labels are compared with predicted labels through Jaccard similarity.
2. A prediction is considered matched when the Jaccard score is above the chosen threshold.
3. The linked Wikidata QIDs are then checked.

With this logic:

- **True Positive (TP)**: a predicted label matches a gold label and the Wikidata QID is also correct
- **False Positive (FP)**: a predicted label either does not match any gold label, or matches a gold label but with the wrong Wikidata QID
- **False Negative (FN)**: a gold label is not found in the predictions, or it is found with the wrong Wikidata QID

The script then computes:

- Precision = `TP / (TP + FP)`
- Recall = `TP / (TP + FN)`
- F1 = `2 * Precision * Recall / (Precision + Recall)`

The repository also includes alternative evaluation scripts for QID-only analyses.

## Results

The `Results/` folder contains:

- raw LLM outputs
- LLM outputs after Wikipedia resolution and Wikidata QID retrieval

## Baselines

The folder `Frameworks_for_baseline/` contains scripts for baseline systems.

In particular:

- **ReLik** is used as a baseline for the named entity experiments
- **DBpedia Spotlight** is used as a baseline for the keyword experiments

Other baseline scripts are also included in the same folder.

## Repository Structure

- `Frameworks_for_baseline/`: baseline methods and comparison systems
- `Gold_Standards/`: manually annotated gold standards
- `Moving_Dataset_Selected_Narratives/`: sampled MOVING narratives used in the experiments
- `Results/`: model outputs and linked outputs with Wikidata QIDs
- `ollama_Named_Entites.py`: named entity extraction with Ollama LLMs
- `ollama_keywords.py`: keyword extraction with Ollama LLMs
- `getWidataIdUsingWikipediaAPIs.py`: Wikipedia-to-Wikidata linking step
- `evaluation.py`: main evaluation script
- `requirements.txt`: Python dependencies

## Notes

- The repository focuses on **small open-source LLMs**.
- Wikidata is used as the reference knowledge base for linking.
- The experiment is designed for knowledge graph population scenarios starting from domain narratives.
