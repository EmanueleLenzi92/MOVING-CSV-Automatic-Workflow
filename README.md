# MOVING CSV Automatic Workflow

This repository contains an automatic workflow for transforming CSV contents into narrative knowledge graphs modelled according to the **Narrative Ontology** <https://dlnarratives.eu/ontology.html>, developed by **ISTI-CNR**, for narratives about mountain value chains.

The source data were collected by the **MOVING Horizon 2020** project <https://www.moving-h2020.eu/>, which gathered data about **454 mountain value chains** across **16 European countries** in CSV format.

## Overview

The workflow is organized into three main modules plus an evaluation area.

## Workflow-module-1

The first module creates narratives starting from the original MOVING CSV data.

Each narrative is structured as a set of events, where every event includes:

- a title
- a textual description

## Workflow-module-2

The second module enriches the narratives with entity extraction and linking.

It uses:

- **Gemma3 12b** to extract keywords from event descriptions (It requires Ollama <https://ollama.com/>)
- **ReLiK** to extract named entities (It requires ReLik <https://github.com/SapienzaNLP/relik> and KILT <https://github.com/facebookresearch/KILT>)

The extracted items are then linked to **Wikidata** entries.

The module also retrieves additional metadata for the linked entities, including:

- images
- coordinates
- textual descriptions

## Workflow-module-3

The third module contains a PHP script that creates the knowledge graph for each narrative enriched in the previous module.

It calls a triplifier (<https://github.com/AIMH-DHgroup/dlnarratives_triplifier>) that converts the data into RDF/OWL according to the **Narrative Ontology**.

As output, it produces:

- **454 OWL files**, one for each value chain
- the corresponding JSON files
- HTML files for visualization as storymaps

## Keywords-and-Named-Entities-Linking-with-Wikidata-Using-Small-LLMs

This folder contains an experiment for evaluating the best LLM and tool to use as the entity/keyword extractor in the second module.

It focuses on:

- keyword extraction
- named entity extraction
- linking to Wikidata
- comparison between small open-source LLMs and baseline tools

## Evaluation

The `Evaluation/` folder contains the graph evaluation stage based on **SPARQL queries**.

It includes:

- 4 SPARQL queries executed against a **Fuseki** endpoint where the OWL files are stored
- the corresponding query results
- `evaluate_sparql_results.py`: it computes precision, recall and F1 score of the SPARQL queries 


## Repository Structure

- `Workflow-module-1/`: narrative generation from MOVING CSV files
- `Workflow-module-2/`: keyword and named entity extraction, Wikidata linking, metadata enrichment
- `Workflow-module-3/`: triplification and OWL/JSON/HTML storymap generation
- `Keywords-and-Named-Entities-Linking-with-Wikidata-Using-Small-LLMs/`: evaluation experiment for extractor selection
- `Evaluation/`: SPARQL-based graph evaluation

## Goal

The overall goal of the repository is to provide an automatic pipeline for converting structured CSV data about mountain value chains into semantically enriched narratives and knowledge graphs based on the Narrative Ontology, allowing inter-story correlation analysis of the mountain value chains.
