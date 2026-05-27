These are the queries that were used to evaluate the MOVING narrative knowledge graph produced through the workflow in order to perform inter-story correlation analysis across the 454 value chains (VCs).

The queries address the following four use cases:

- the VCs that face challenges related to drought (Q1)
- the VCs that include innovations related to marketing strategies (Q2)
- the VCs whose reference mountain range is composed of limestone (Q3)
- the VCs whose reference mountain range is the Alps (Q4)

For each use case, two queries were carried out:

- the first one was executed considering the event-based narrative knowledge graph structure
- the second one was executed considering the whole graph, relying only on the keywords extracted independently from the narrative events in which they appear

## Event-Based Narrative Knowledge Graph

### Q1. VCs that face challenges related to drought

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 

SELECT DISTINCT ?title ?narrative ?CountryName
WHERE {

    ?narrative rdfs:label ?title ;
      		narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    {

        ?event1
            narra:partOfNarrative ?narrative ;
            narra:is_identified_by <https://dlnarratives.eu/appellation/Challenges>;
            narra:hasEntity ?entity .

        VALUES ?entity {
            <https://dlnarratives.eu/resource/Q43059> 
            <https://dlnarratives.eu/resource/Q283>   
            <https://dlnarratives.eu/resource/Q1313> 
        }
    }

    UNION

    {

        ?event2
            narra:partOfNarrative ?narrative ;
            narra:is_identified_by <https://dlnarratives.eu/appellation/Challenges>;
            narra:hasEntity <https://dlnarratives.eu/resource/Q125928> ;
            narra:hasEntity ?pairedEntity .

        VALUES ?pairedEntity {
            <https://dlnarratives.eu/resource/Q43059> 
            <https://dlnarratives.eu/resource/Q283>   
            <https://dlnarratives.eu/resource/Q1313> 
        }
    }
}
order by ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Murbodner Beef","https://dlnarratives.eu/narrative/N239_VC_14_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Bio vom Berg (translated: Organic products from mountain areas)","https://dlnarratives.eu/narrative/N326_VC_19_AT","Austria"
"Sheep wool","https://dlnarratives.eu/narrative/N226_VC_13_CZ","Czech Republic"
"High Quality Beef Production","https://dlnarratives.eu/narrative/N26_VC_02_CZ","Czech Republic"
"Goats - dairy products","https://dlnarratives.eu/narrative/N62_VC_04_CZ","Czech Republic"
"Picodon AOP","https://dlnarratives.eu/narrative/N120_VC_07_FR1","France"
"Unedo arbutus honey-PDO","https://dlnarratives.eu/narrative/N138_VC_08_FR","France"
"Extensive and pastoral livestock farming","https://dlnarratives.eu/narrative/N29_VC_02_FR1","France"
"Diversification strategies of conventional agriculture in large irrigated field crops","https://dlnarratives.eu/narrative/N9_VC_01_FR1","France"
"Activities connected to PDO olive oil products of designated origin and geographical indication","https://dlnarratives.eu/narrative/N121_VC_07_GR","Greece"
"Traditional potato from Olaszfalu","https://dlnarratives.eu/narrative/N160_VC_09_HU","Hungary"
"Traditional cabbeges from Olaszfalu","https://dlnarratives.eu/narrative/N176_VC_10_HU","Hungary"
"Logging, timber production, afforestation, maintenance, leasure, sport","https://dlnarratives.eu/narrative/N194_VC_11_HU","Hungary"
"Ecological garden in Pallagvölgy - a joint enterprise","https://dlnarratives.eu/narrative/N316_VC_18_HU","Hungary"
"The tastes of Monostor","https://dlnarratives.eu/narrative/N333_VC_19_HU","Hungary"
"Sustainable local food system project - Kóspallag","https://dlnarratives.eu/narrative/N33_VC_22_HU","Hungary"
"Pumpkin seed products in Boldva","https://dlnarratives.eu/narrative/N67_VC_04_HU","Hungary"
"The beekeeper produces organic honeys, honey aged in barrels and mead (fermented honey drink)","https://dlnarratives.eu/narrative/N248_VC_14_IT","Italy"
"The farm sustainably produces apple and derived alcoholic beverages (cider)","https://dlnarratives.eu/narrative/N57_VC_27_IT","Italy"
"Fly fishing in Hemsedal","https://dlnarratives.eu/narrative/N218_VC_12_SCA","Norway"
"DO Douro Wine - Douro Superior - Vila Nova de Foz Côa","https://dlnarratives.eu/narrative/N14_VC_01_PT","Portugal"
"CERTIFIED ECOTOURISM","https://dlnarratives.eu/narrative/N53_VC_03_RO","Romania"
"Vaccinium Myrtillus from National Park Kopaonik. Wild blue berry picking and processing","https://dlnarratives.eu/narrative/N110_VC_06_SER","Serbia"
"Trout fish produced in clear and fresh water ponds","https://dlnarratives.eu/narrative/N128_VC_07_SER","Serbia"
"Raspberries produced in the area of Arilje region (western Serbia), PDO","https://dlnarratives.eu/narrative/N253_VC_14_SER","Serbia"
"Berries cultivated in Kopaonik Mountain/ National park, Dinaric Alps region; other mountain areas","https://dlnarratives.eu/narrative/N304_VC_17_SER","Serbia"
"Wild mushrooms collected in the mountainous area of Kopaonik mountain","https://dlnarratives.eu/narrative/N322_VC_18_SER","Serbia"
"Spring and mineral waters from mountains - sold as natural or flavored with local wild herbs","https://dlnarratives.eu/narrative/N323_VC_18_SK","Slovakia"
"Label of the Swiss Mother Cow Association (association des vaches mères suisse) for beef from suckler cows or suckler cows in accordance with the principles of organic farming","https://dlnarratives.eu/narrative/N116_VC_54_CH2","Switzerland"
"PDO cheese made from raw and whole cow's milk. It's consumption is unique in that it is eaten in the form of rosettes obtained with the help of the 'girolle'","https://dlnarratives.eu/narrative/N135_VC_08_CH2","Switzerland"
"Gruyère is a typical Swiss cheese, produced in the mountain pastures in summer","https://dlnarratives.eu/narrative/N154_VC_09_CH2","Switzerland"
"Beer","https://dlnarratives.eu/narrative/N170_VC_10_CH1","Switzerland"
"'GranAlpin' cereal products","https://dlnarratives.eu/narrative/N206_VC_12_CH1","Switzerland"
"It is the only remaining horse breed of Swiss origin specialised in driving but with a versatile profile (trekking, sport)","https://dlnarratives.eu/narrative/N242_VC_14_CH2","Switzerland"
"Timber harvesting","https://dlnarratives.eu/narrative/N260_VC_15_CH2","Switzerland"
"Trout farming in the Doubs river","https://dlnarratives.eu/narrative/N278_VC_16_CH2","Switzerland"
"Wine","https://dlnarratives.eu/narrative/N61_VC_04_CH1","Switzerland"
"Traditional alpine herbs","https://dlnarratives.eu/narrative/N79_VC_05_CH1","Switzerland"
"Cheese made from cow's milk, eaten raw and baked","https://dlnarratives.eu/narrative/N94_VC_43_CH2","Switzerland"
"Jogitz yogurt made from Goat's milk (several flavours)","https://dlnarratives.eu/narrative/N98_VC_06_CH2","Switzerland"
"cow's milk milk that has not been pasteurised, sterilised or thermised and is sold directly","https://dlnarratives.eu/narrative/N99_VC_45_CH2","Switzerland"
"Value Chain","https://dlnarratives.eu/narrative/N19_VC_20_TR","Turkey"
"Aydın Fig is an agriicultural product that is consumed fresh and dried","https://dlnarratives.eu/narrative/N273_VC_15_TR","Turkey"
"Fresh Fruit (Apple)","https://dlnarratives.eu/narrative/N75_VC_04_TR","Turkey"
"Scotch whisky (PDO) which rely on clean water, peat and barley. Also an important tourist attraction","https://dlnarratives.eu/narrative/N4_VC_19_UK","United Kingdom"
```

### Q2. VCs that include innovations related to marketing strategies

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 


SELECT DISTINCT ?title ?narrative ?CountryName


WHERE {

    ?narrative rdfs:label ?title ;
              narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    ?event1 narra:partOfNarrative ?narrative ;
            narra:is_identified_by <https://dlnarratives.eu/appellation/Innovation>;
            narra:hasEntity ?mandatoryEntity .

    VALUES ?mandatoryEntity {
        <https://dlnarratives.eu/resource/Q39809> 
        <https://dlnarratives.eu/resource/Q1363963> 
    }


}
orderby ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"Holzwelt Murau (transl.: World of wood Murau)","https://dlnarratives.eu/narrative/N114_VC_07_AT","Austria"
"Murauer Heumilch (transl.: hay milk)","https://dlnarratives.eu/narrative/N151_VC_09_AT","Austria"
"Osttiroler Mountain Lamb ('Osttiroler Berglamm')","https://dlnarratives.eu/narrative/N168_VC_10_AT","Austria"
"Murbodner Potatoes ('Murbodner Erdäpfel')","https://dlnarratives.eu/narrative/N185_VC_11_AT","Austria"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Lungauer Eachtling","https://dlnarratives.eu/narrative/N204_VC_12_AT","Austria"
"Mölltal-Glockner Lamm (engl. lamb)","https://dlnarratives.eu/narrative/N223_VC_13_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Sheep farmers from Weiz ('Weizer Schafbauern')","https://dlnarratives.eu/narrative/N257_VC_15_AT","Austria"
"Kärntna Låxn","https://dlnarratives.eu/narrative/N275_VC_16_AT","Austria"
"Ausseerland Seesaibling und Forelle (engl. artic char and trout from the Region Ausseerland)","https://dlnarratives.eu/narrative/N291_VC_17_AT","Austria"
"Murtaler pumpkin seed oil ('Kürbiskernöl')","https://dlnarratives.eu/narrative/N308_VC_18_AT","Austria"
"Bio vom Berg (translated: Organic products from mountain areas)","https://dlnarratives.eu/narrative/N326_VC_19_AT","Austria"
"Almenland Herbs","https://dlnarratives.eu/narrative/N41_VC_03_AT","Austria"
"Almenland Fish","https://dlnarratives.eu/narrative/N59_VC_04_AT","Austria"
"Styria Beef","https://dlnarratives.eu/narrative/N5_VC_20_AT","Austria"
"Almenland Honey","https://dlnarratives.eu/narrative/N77_VC_05_AT","Austria"
"Almenland Schnaps","https://dlnarratives.eu/narrative/N95_VC_06_AT","Austria"
"'LINBUL FARM' (ONLINE SALES OF GRASS-FED BEEF)","https://dlnarratives.eu/narrative/N115_VC_07_BG","Bulgaria"
"'FOOD FROM THE MOUNTAIN' FARMERS' ASSOCIATION","https://dlnarratives.eu/narrative/N152_VC_09_BG","Bulgaria"
"ALL SEASON MOUNTAIN TOURISM (BANSKO RESORT)","https://dlnarratives.eu/narrative/N169_VC_10_BG","Bulgaria"
"'MEADOWS IN THE MOUNTAINS' FESTIVAL","https://dlnarratives.eu/narrative/N205_VC_12_BG","Bulgaria"
"CULINARY GUIDEBOOK TO THE EASTERN RHODOPES","https://dlnarratives.eu/narrative/N6_VC_20_BG","Bulgaria"
"Marmelade","https://dlnarratives.eu/narrative/N136_VC_08_CZ","Czech Republic"
"Valašský frgál","https://dlnarratives.eu/narrative/N155_VC_09_CZ","Czech Republic"
"Fruit spirit (hard liquer)","https://dlnarratives.eu/narrative/N208_VC_12_CZ","Czech Republic"
"Sheep wool","https://dlnarratives.eu/narrative/N226_VC_13_CZ","Czech Republic"
"High Quality Beef Production","https://dlnarratives.eu/narrative/N26_VC_02_CZ","Czech Republic"
"Goats - dairy products","https://dlnarratives.eu/narrative/N62_VC_04_CZ","Czech Republic"
"Dry cold meats NON -PDO, Ficatelli, sausages and pieces","https://dlnarratives.eu/narrative/N101_VC_06_FR","France"
"honey - Miels de Corse-Mele di Corsica - PDO","https://dlnarratives.eu/narrative/N119_VC_07_FR","France"
"Unedo arbutus honey-PDO","https://dlnarratives.eu/narrative/N138_VC_08_FR","France"
"'Corsican chestnut flour - Farina castagnina corsa' PDO","https://dlnarratives.eu/narrative/N157_VC_09_FR","France"
"Agriturism - turistic and pedagogic welcome in farms","https://dlnarratives.eu/narrative/N158_VC_09_FR1","France"
"Corsican aromatic plant : Immortelle de Corse - 'hélichrysum italicum ssp italicum'","https://dlnarratives.eu/narrative/N173_VC_10_FR","France"
"Organic still wine production","https://dlnarratives.eu/narrative/N174_VC_10_FR1","France"
"Poultry farming, integration by the cooperative","https://dlnarratives.eu/narrative/N211_VC_12_FR1","France"
"Mountain apple production","https://dlnarratives.eu/narrative/N314_VC_18_FR","France"
"Perivillage production - vegetable and fruit","https://dlnarratives.eu/narrative/N331_VC_19_FR","France"
"Market gardening, diversified with organic value chain and local channels distribution","https://dlnarratives.eu/narrative/N47_VC_03_FR1","France"
"PDO-Dry cured pork","https://dlnarratives.eu/narrative/N83_VC_05_FR","France"
"Production of Graviera cheese (Gruyere) in the area Amari, on the western slopes of Psiloritis mountain","https://dlnarratives.eu/narrative/N10_VC_01_GR","Greece"
"Activites for the production and handling of PGI potatoes","https://dlnarratives.eu/narrative/N11_VC_20_GR","Greece"
"Activities based on chestnuts from the mountainous areas of the western most part of Crete","https://dlnarratives.eu/narrative/N175_VC_10_GR","Greece"
"Apicature activities in mountainous areas of Kissamos, Chania","https://dlnarratives.eu/narrative/N193_VC_11_GR","Greece"
"Processing of milk and wheat for the production of traditional frumenty in the mountainous area of Anogeia","https://dlnarratives.eu/narrative/N25_VC_21_GR","Greece"
"Activities connected to legumes, traditinally cultivated in Feneos area in the Peloponnese","https://dlnarratives.eu/narrative/N297_VC_17_GR","Greece"
"Epirus Feta is PDO cheese made of sheep and goat milk in the Epirus region, Ammotopos Arta","https://dlnarratives.eu/narrative/N332_VC_19_GR","Greece"
"Production of traditional edible products from the mountainous area of Gergeri, Fortyna (Psiloritis mountain)","https://dlnarratives.eu/narrative/N66_VC_04_GR","Greece"
"Activities connected to the production of wine in the PDO Peza zone, at eastern Crete","https://dlnarratives.eu/narrative/N85_VC_05_GR","Greece"
"Biscuits and chocolate from Pannonhalma","https://dlnarratives.eu/narrative/N122_VC_07_HU","Hungary"
"Cosmetics, health products and herbal tea in Pannonhalma","https://dlnarratives.eu/narrative/N141_VC_08_HU","Hungary"
"Skiing and downhill cycling in Eplény","https://dlnarratives.eu/narrative/N230_VC_13_HU","Hungary"
"Honey from the Bakony mountains","https://dlnarratives.eu/narrative/N247_VC_14_HU","Hungary"
"Organic garlic from Olaszfalu","https://dlnarratives.eu/narrative/N265_VC_15_HU","Hungary"
"Ecological garden in Pallagvölgy - a joint enterprise","https://dlnarratives.eu/narrative/N316_VC_18_HU","Hungary"
"Gastro village Köveskál Eat the View","https://dlnarratives.eu/narrative/N31_VC_02_HU","Hungary"
"The tastes of Monostor","https://dlnarratives.eu/narrative/N333_VC_19_HU","Hungary"
"Nivegy-valley small family wineries","https://dlnarratives.eu/narrative/N49_VC_03_HU","Hungary"
"Pumpkin seed products in Boldva","https://dlnarratives.eu/narrative/N67_VC_04_HU","Hungary"
"Trizs, the fruitful village","https://dlnarratives.eu/narrative/N86_VC_05_HU","Hungary"
"Biodistretto Val di Vara (Organic district in Val di Vara)","https://dlnarratives.eu/narrative/N101_VC_45_IT","Italy"
"Through a ICT solution is established an interactive costumers participation in farm production","https://dlnarratives.eu/narrative/N105_VC_06_IT","Italy"
"Bovine meat of race Limousine and flour from corn 'ottofile'","https://dlnarratives.eu/narrative/N114_VC_52_IT","Italy"
"This farm produces high quality certified milk and cheese in a mountain nature park","https://dlnarratives.eu/narrative/N231_VC_13_IT","Italy"
"The beekeeper produces organic honeys, honey aged in barrels and mead (fermented honey drink)","https://dlnarratives.eu/narrative/N248_VC_14_IT","Italy"
"Pontepietra is a small organic farm that cultivates medicinal and aromatic herbs and practices the collection of wild herbs","https://dlnarratives.eu/narrative/N27_VC_21_IT","Italy"
"The rural butchery","https://dlnarratives.eu/narrative/N317_VC_18_IT","Italy"
"Chestnut flour coming from the rediscovered chesnut cultivation activities in the area","https://dlnarratives.eu/narrative/N32_VC_02_IT","Italy"
"Old varieties of potatos (Walser Kartoffeln)","https://dlnarratives.eu/narrative/N34_VC_22_IT","Italy"
"Alto Trentino, organic wine production for premium wines","https://dlnarratives.eu/narrative/N40_VC_23_IT","Italy"
"The farm sustainably produces apple and derived alcoholic beverages (cider)","https://dlnarratives.eu/narrative/N57_VC_27_IT","Italy"
"Island Wine from Lipari, Aeolian Islands","https://dlnarratives.eu/narrative/N66_VC_29_IT","Italy"
"Cultivation and processing of organic apple on abandoned lands","https://dlnarratives.eu/narrative/N68_VC_04_IT","Italy"
"Volcanic Wine (Sicily-Etna)","https://dlnarratives.eu/narrative/N69_VC_30_IT","Italy"
"Different varieties of potatos produce under specific rules","https://dlnarratives.eu/narrative/N71_VC_31_IT","Italy"
"'Di Iorio' is a old (by 1750) artisanal confectionery in Southern Italy specialized in nougat production","https://dlnarratives.eu/narrative/N79_VC_35_IT","Italy"
"Caciocavallo cheese form southern Dauni mountains","https://dlnarratives.eu/narrative/N81_VC_36_IT","Italy"
"Central Appenninian (Abruzzo)","https://dlnarratives.eu/narrative/N83_VC_37_IT","Italy"
"Di Nucci renews the tradition of cheese production, supporting breeders and sustainable livestock","https://dlnarratives.eu/narrative/N87_VC_05_IT","Italy"
"La Cattedra- Commons farming for social and educational purposes- Asiago Plateau","https://dlnarratives.eu/narrative/N93_VC_42_IT","Italy"
"Potatos","https://dlnarratives.eu/narrative/N143_VC_08_NMK","North Macedonia"
"Rural tourism in Maleshevija (North Macedonia) - Mountain related tourism","https://dlnarratives.eu/narrative/N249_VC_14_NMK","North Macedonia"
"Kozhuvchanka - Drinking water produced from a spring in a Kozhuv Mountain","https://dlnarratives.eu/narrative/N284_VC_16_NMK","North Macedonia"
"Wine - Vranec","https://dlnarratives.eu/narrative/N318_VC_18_NMK","North Macedonia"
"Honey in Maleshevija (North Macedonia)","https://dlnarratives.eu/narrative/N33_VC_02_NMK","North Macedonia"
"Mushrooms - NTFP","https://dlnarratives.eu/narrative/N51_VC_03_NMK","North Macedonia"
"Medicinal herbs - NTFP","https://dlnarratives.eu/narrative/N88_VC_05_NMK","North Macedonia"
"Organic primitive grain muesli","https://dlnarratives.eu/narrative/N109_VC_06_SCA","Norway"
"Barley flour and groats","https://dlnarratives.eu/narrative/N127_VC_07_SCA","Norway"
"PGI (Protected Geographical Indication) Goat cheese / Undredal Brown Cheese","https://dlnarratives.eu/narrative/N164_VC_09_SCA","Norway"
"Artisanal beer from Stjordal","https://dlnarratives.eu/narrative/N17_VC_01_SCA","Norway"
"Fish farming - PDO (Protected Designation of Origin) Rakfish from Valdres","https://dlnarratives.eu/narrative/N199_VC_11_SCA","Norway"
"Unsprayed berries","https://dlnarratives.eu/narrative/N235_VC_13_SCA","Norway"
"Nature-based Tourism","https://dlnarratives.eu/narrative/N287_VC_16_SCA","Norway"
"Sami Handicraft - Duodji","https://dlnarratives.eu/narrative/N321_VC_18_SCA","Norway"
"PGI (Protected Geographical Indication) Cider from Hardanger","https://dlnarratives.eu/narrative/N36_VC_02_SCA","Norway"
"Meat products (dry and cured meat)","https://dlnarratives.eu/narrative/N72_VC_04_SCA","Norway"
"Free ranged animals","https://dlnarratives.eu/narrative/N91_VC_05_SCA","Norway"
"'MOZZARELLA'","https://dlnarratives.eu/narrative/N217_VC_12_RO","Romania"
"'GASTRO LOCAL'","https://dlnarratives.eu/narrative/N320_VC_18_RO","Romania"
"Vaccinium Myrtillus from National Park Kopaonik. Wild blue berry picking and processing","https://dlnarratives.eu/narrative/N110_VC_06_SER","Serbia"
"Vlasina honey PDO","https://dlnarratives.eu/narrative/N165_VC_09_SER","Serbia"
"Pirot hard cheese from sheep and cow milk","https://dlnarratives.eu/narrative/N181_VC_10_SER","Serbia"
"White cheese in brine, produced of mixed milk - cow and sheep milk","https://dlnarratives.eu/narrative/N200_VC_11_SER","Serbia"
"Raspberries produced in the area of Arilje region (western Serbia), PDO","https://dlnarratives.eu/narrative/N253_VC_14_SER","Serbia"
"Berries cultivated in Kopaonik Mountain/ National park, Dinaric Alps region; other mountain areas","https://dlnarratives.eu/narrative/N304_VC_17_SER","Serbia"
"Wild mushrooms collected in the mountainous area of Kopaonik mountain","https://dlnarratives.eu/narrative/N322_VC_18_SER","Serbia"
"Wool handmade carpets with traditional motives","https://dlnarratives.eu/narrative/N49_VC_25_SER","Serbia"
"Dried saussage made of localy produced sheep, goat, beef or donkey meat mixture, PDO application","https://dlnarratives.eu/narrative/N58_VC_27_SER","Serbia"
"Potatoes grown in high altitudes, known by their quality","https://dlnarratives.eu/narrative/N73_VC_04_SER","Serbia"
"E-commerce and governement incentives to support domestic game meat consumption","https://dlnarratives.eu/narrative/N236_VC_13_SK","Slovakia"
"A combination of beekeeping and agrotourism","https://dlnarratives.eu/narrative/N288_VC_16_SK","Slovakia"
"Lamb meat production - focusing on local consumers instead of export","https://dlnarratives.eu/narrative/N56_VC_03_SK","Slovakia"
"Wines of the Contraviesa","https://dlnarratives.eu/narrative/N100_VC_06_ES","Spain"
"Quince juice and vinegar from Carcabuey and Priego de Córdoba","https://dlnarratives.eu/narrative/N100_VC_45_ES","Spain"
"Leather handcraft","https://dlnarratives.eu/narrative/N104_VC_47_ES","Spain"
"Mycology in the mountains of Soria","https://dlnarratives.eu/narrative/N111_VC_50_ES","Spain"
"Honey","https://dlnarratives.eu/narrative/N156_VC_09_ES","Spain"
"Olive oil Protected Designation of Origin Sierra de Segura","https://dlnarratives.eu/narrative/N172_VC_10_ES","Spain"
"Euskal Txerria Pig","https://dlnarratives.eu/narrative/N23_VC_21_ES","Spain"
"Cheese from Picos de Europa","https://dlnarratives.eu/narrative/N262_VC_15_ES","Spain"
"Spanish fighting bull (Toro de Lidia)","https://dlnarratives.eu/narrative/N296_VC_17_ES","Spain"
"Spirits - anis and cherry drinks","https://dlnarratives.eu/narrative/N45_VC_03_ES","Spain"
"Meat from Cervera de Pisuerga and the Palencia Mountains","https://dlnarratives.eu/narrative/N47_VC_25_ES","Spain"
"Veal from Cantabria","https://dlnarratives.eu/narrative/N52_VC_26_ES","Spain"
"Botillo from El Bierzo","https://dlnarratives.eu/narrative/N61_VC_28_ES","Spain"
"Suckling goat from Malaga","https://dlnarratives.eu/narrative/N70_VC_31_ES","Spain"
"Indigenous pulses, cereals and fruits of Ascara (Jaca)","https://dlnarratives.eu/narrative/N78_VC_35_ES","Spain"
"Production of spirits, liquors, and brandies, following traditional recipes","https://dlnarratives.eu/narrative/N82_VC_05_ES","Spain"
"Cherries from Castillo de Locubín","https://dlnarratives.eu/narrative/N85_VC_38_ES","Spain"
"Grazalema wool blankets, textile craftsmanship","https://dlnarratives.eu/narrative/N86_VC_39_ES","Spain"
"Subbética Ecológica, production and consumption of organic food","https://dlnarratives.eu/narrative/N92_VC_42_ES","Spain"
"Mantecados de Rute, Traditional Christmas cakes made mainly from lard","https://dlnarratives.eu/narrative/N97_VC_44_ES","Spain"
"Beer","https://dlnarratives.eu/narrative/N170_VC_10_CH1","Switzerland"
"'GranAlpin' cereal products","https://dlnarratives.eu/narrative/N206_VC_12_CH1","Switzerland"
"It is the only remaining horse breed of Swiss origin specialised in driving but with a versatile profile (trekking, sport)","https://dlnarratives.eu/narrative/N242_VC_14_CH2","Switzerland"
"'Soglio' cosmetics","https://dlnarratives.eu/narrative/N259_VC_15_CH1","Switzerland"
"'Patrimont Switzerland' saving old breeds","https://dlnarratives.eu/narrative/N277_VC_16_CH1","Switzerland"
"Touristic services","https://dlnarratives.eu/narrative/N310_VC_18_CH1","Switzerland"
"'Fruttipertutti'","https://dlnarratives.eu/narrative/N37_VC_23_CH1","Switzerland"
"'ZOJA' apple tasting box","https://dlnarratives.eu/narrative/N43_VC_24_CH1","Switzerland"
"'AlpenPionier' hemp products","https://dlnarratives.eu/narrative/N46_VC_25_CH1","Switzerland"
"'Alpinavera' Nuts","https://dlnarratives.eu/narrative/N51_VC_26_CH1","Switzerland"
"'AlpenHirt' dried meat","https://dlnarratives.eu/narrative/N60_VC_28_CH1","Switzerland"
"Alps Art Academy and Art Safiental","https://dlnarratives.eu/narrative/N67_VC_30_CH1","Switzerland"
"Promotion and development of horse meat from the swiss jura","https://dlnarratives.eu/narrative/N80_VC_05_CH2","Switzerland"
"cow's milk milk that has not been pasteurised, sterilised or thermised and is sold directly","https://dlnarratives.eu/narrative/N99_VC_45_CH2","Switzerland"
"Special types of chestnut trees for candied which is at the east of the İzmir on Bozdağ Mountains","https://dlnarratives.eu/narrative/N149_VC_08_TR","Turkey"
"Highland cows - bred for genes and cultural status not necessarily for meat/dairy- Lochaber","https://dlnarratives.eu/narrative/N256_VC_14_UK","United Kingdom"
"Jewellery made from heather (Perth and Kinross LAU1)","https://dlnarratives.eu/narrative/N274_VC_15_UK","United Kingdom"
"Independent and guided visits motivated by visiting wild places and seeing wildlife","https://dlnarratives.eu/narrative/N30_VC_21_UK","United Kingdom"
"Mountain region hard white cows milk cheese with PGI from Caerphillly LAU1 region within Wales","https://dlnarratives.eu/narrative/N40_VC_02_UK","United Kingdom"
"Scottish Heather honey- heather that is marketed as produced from heather moorland","https://dlnarratives.eu/narrative/N58_VC_03_UK","United Kingdom"
```

### Q3. VCs whose reference mountain range is composed of limestone

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 

SELECT DISTINCT ?title ?narrative  ?CountryName

WHERE {

    ?narrative rdfs:label ?title ;
              narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    ?event1 narra:partOfNarrative ?narrative ;
            narra:is_identified_by <https://dlnarratives.eu/appellation/Mountain_landscape_and_reference_chains>;
            narra:hasEntity ?mandatoryEntity .

    VALUES ?mandatoryEntity {
        <https://dlnarratives.eu/resource/Q23757> 
    }


}
orderby ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"Osttiroler Mountain Lamb ('Osttiroler Berglamm')","https://dlnarratives.eu/narrative/N168_VC_10_AT","Austria"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Sheep farmers from Weiz ('Weizer Schafbauern')","https://dlnarratives.eu/narrative/N257_VC_15_AT","Austria"
"Almenland Herbs","https://dlnarratives.eu/narrative/N41_VC_03_AT","Austria"
"Almenland Fish","https://dlnarratives.eu/narrative/N59_VC_04_AT","Austria"
"Almenland Honey","https://dlnarratives.eu/narrative/N77_VC_05_AT","Austria"
"Almenland Schnaps","https://dlnarratives.eu/narrative/N95_VC_06_AT","Austria"
"Dittany grows widely and is cultivated in Embaros, Dicti mountain area","https://dlnarratives.eu/narrative/N229_VC_13_GR","Greece"
"Prickly pear cactus is a perennial shrub that grows everywhere in the dry, rocky terrain of Dicti and in the region of Viannos","https://dlnarratives.eu/narrative/N246_VC_14_GR","Greece"
"Absinthe is a set of spirits made from absinth plants, to be mixed with water for consumption","https://dlnarratives.eu/narrative/N108_VC_49_CH2","Switzerland"
"Goat's milk cheese made by hand directly on the mountain pasture","https://dlnarratives.eu/narrative/N113_VC_51_CH2","Switzerland"
"PDO cheese made from raw and whole cow's milk. It's consumption is unique in that it is eaten in the form of rosettes obtained with the help of the 'girolle'","https://dlnarratives.eu/narrative/N135_VC_08_CH2","Switzerland"
"Salted cake made of leavened dough and covered with a cream-based mixture","https://dlnarratives.eu/narrative/N188_VC_11_CH2","Switzerland"
"alpine pasture cheese with organic sheep's milk","https://dlnarratives.eu/narrative/N207_VC_12_CH2","Switzerland"
"It is the only remaining horse breed of Swiss origin specialised in driving but with a versatile profile (trekking, sport)","https://dlnarratives.eu/narrative/N242_VC_14_CH2","Switzerland"
"Trout farming in the Doubs river","https://dlnarratives.eu/narrative/N278_VC_16_CH2","Switzerland"
"Local mustard produces and transformed on the farm","https://dlnarratives.eu/narrative/N311_VC_18_CH2","Switzerland"
"Cooperative that produces malt from local barley","https://dlnarratives.eu/narrative/N4_VC_01_CH2","Switzerland"
"Promotion and development of horse meat from the swiss jura","https://dlnarratives.eu/narrative/N80_VC_05_CH2","Switzerland"
"Different types of honey from the Jura Vaudois (moutain, flower, fir tree, forest). The beehives are in winter at l'Isle in the plains and in summer in the mountains","https://dlnarratives.eu/narrative/N84_VC_38_CH2","Switzerland"
"Plants grown, dried and transformed into herbal tea or cosmetic products directly on the farm","https://dlnarratives.eu/narrative/N90_VC_41_CH2","Switzerland"
"Cheese made from cow's milk, eaten raw and baked","https://dlnarratives.eu/narrative/N94_VC_43_CH2","Switzerland"
"Jogitz yogurt made from Goat's milk (several flavours)","https://dlnarratives.eu/narrative/N98_VC_06_CH2","Switzerland"
```

### Q4. VCs whose reference mountain range is the Alps

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 

SELECT DISTINCT ?title ?narrative  ?CountryName

WHERE {

    ?narrative rdfs:label ?title ;
              narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    ?event1 narra:partOfNarrative ?narrative ;
            narra:is_identified_by <https://dlnarratives.eu/appellation/Mountain_landscape_and_reference_chains>;
            narra:hasEntity ?mandatoryEntity .

    VALUES ?mandatoryEntity {
        <https://dlnarratives.eu/resource/Q1286> 
    <https://dlnarratives.eu/resource/Q63817>  
    <https://dlnarratives.eu/resource/Q190348>
    <https://dlnarratives.eu/resource/Q64848883>   
    <https://dlnarratives.eu/resource/Q25220> 
    <https://dlnarratives.eu/resource/Q327434>   
    <https://dlnarratives.eu/resource/Q1251>
     
    }

}

orderby ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"Holzwelt Murau (transl.: World of wood Murau)","https://dlnarratives.eu/narrative/N114_VC_07_AT","Austria"
"Murauer Beer","https://dlnarratives.eu/narrative/N132_VC_08_AT","Austria"
"Murauer Heumilch (transl.: hay milk)","https://dlnarratives.eu/narrative/N151_VC_09_AT","Austria"
"Osttiroler Mountain Lamb ('Osttiroler Berglamm')","https://dlnarratives.eu/narrative/N168_VC_10_AT","Austria"
"Murbodner Potatoes ('Murbodner Erdäpfel')","https://dlnarratives.eu/narrative/N185_VC_11_AT","Austria"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Lungauer Eachtling","https://dlnarratives.eu/narrative/N204_VC_12_AT","Austria"
"Wines of Kitzek im Sausal, South Styria","https://dlnarratives.eu/narrative/N21_VC_21_AT","Austria"
"Mölltal-Glockner Lamm (engl. lamb)","https://dlnarratives.eu/narrative/N223_VC_13_AT","Austria"
"Murbodner Beef","https://dlnarratives.eu/narrative/N239_VC_14_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Sheep farmers from Weiz ('Weizer Schafbauern')","https://dlnarratives.eu/narrative/N257_VC_15_AT","Austria"
"Kärntna Låxn","https://dlnarratives.eu/narrative/N275_VC_16_AT","Austria"
"Ausseerland Seesaibling und Forelle (engl. artic char and trout from the Region Ausseerland)","https://dlnarratives.eu/narrative/N291_VC_17_AT","Austria"
"Murtaler pumpkin seed oil ('Kürbiskernöl')","https://dlnarratives.eu/narrative/N308_VC_18_AT","Austria"
"Bio vom Berg (translated: Organic products from mountain areas)","https://dlnarratives.eu/narrative/N326_VC_19_AT","Austria"
"Almenland Herbs","https://dlnarratives.eu/narrative/N41_VC_03_AT","Austria"
"Almenland Fish","https://dlnarratives.eu/narrative/N59_VC_04_AT","Austria"
"Styria Beef","https://dlnarratives.eu/narrative/N5_VC_20_AT","Austria"
"Almenland Honey","https://dlnarratives.eu/narrative/N77_VC_05_AT","Austria"
"Almenland Schnaps","https://dlnarratives.eu/narrative/N95_VC_06_AT","Austria"
"Stone fruits: peach and abricot","https://dlnarratives.eu/narrative/N102_VC_06_FR1","France"
"Picodon AOP","https://dlnarratives.eu/narrative/N120_VC_07_FR1","France"
"Fruits with pips","https://dlnarratives.eu/narrative/N139_VC_08_FR1","France"
"Agriturism - turistic and pedagogic welcome in farms","https://dlnarratives.eu/narrative/N158_VC_09_FR1","France"
"Organic still wine production","https://dlnarratives.eu/narrative/N174_VC_10_FR1","France"
"Garlic production","https://dlnarratives.eu/narrative/N192_VC_11_FR1","France"
"Poultry farming, integration by the cooperative","https://dlnarratives.eu/narrative/N211_VC_12_FR1","France"
"Extensive and pastoral livestock farming","https://dlnarratives.eu/narrative/N29_VC_02_FR1","France"
"Market gardening, diversified with organic value chain and local channels distribution","https://dlnarratives.eu/narrative/N47_VC_03_FR1","France"
"Clairette de Die - Wine production targeted towards export","https://dlnarratives.eu/narrative/N65_VC_04_FR1","France"
"Organic, aromatic and medicinal plants","https://dlnarratives.eu/narrative/N84_VC_05_FR1","France"
"Diversification strategies of conventional agriculture in large irrigated field crops","https://dlnarratives.eu/narrative/N9_VC_01_FR1","France"
"Dairy industry","https://dlnarratives.eu/narrative/N103_VC_46_IT","Italy"
"organic eggs","https://dlnarratives.eu/narrative/N105_VC_47_IT","Italy"
"With a open-air grazing in a regional natural park, it is produced and processed high-quality milk","https://dlnarratives.eu/narrative/N107_VC_48_IT","Italy"
"Alto Trentino, organic wine production for premium wines","https://dlnarratives.eu/narrative/N40_VC_23_IT","Italy"
"The farm sustainably produces apple and derived alcoholic beverages (cider)","https://dlnarratives.eu/narrative/N57_VC_27_IT","Italy"
"Grapevine on terraces- Valtellina","https://dlnarratives.eu/narrative/N77_VC_34_IT","Italy"
"apples and derivatives and other agricoltural products","https://dlnarratives.eu/narrative/N87_VC_39_IT","Italy"
"PEFC certified wood from collective forests management","https://dlnarratives.eu/narrative/N89_VC_40_IT","Italy"
"A cooperative dairy of local farmers enhancing local traditional cheese production","https://dlnarratives.eu/narrative/N96_VC_43_IT","Italy"
"Processed pork meat","https://dlnarratives.eu/narrative/N98_VC_44_IT","Italy"
"Merlot from Alps - Ticino","https://dlnarratives.eu/narrative/N118_VC_CH_01","Switzerland"
"Traditional alpine dairy products","https://dlnarratives.eu/narrative/N3_VC_01_CH1","Switzerland"
```

## Whole Graph Based Only on Extracted Keywords


### Q1. VCs that face challenges related to drought

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 

SELECT DISTINCT ?title ?narrative ?CountryName
WHERE {

    ?narrative rdfs:label ?title ;
      		narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    {

        ?event1
            narra:partOfNarrative ?narrative ;
            narra:hasEntity ?entity .

        VALUES ?entity {
            <https://dlnarratives.eu/resource/Q43059> 
            <https://dlnarratives.eu/resource/Q283>   
            <https://dlnarratives.eu/resource/Q1313> 
        }
    }

    UNION

    {

        ?event2
            narra:partOfNarrative ?narrative ;
            narra:hasEntity <https://dlnarratives.eu/resource/Q125928> ;
            narra:hasEntity ?pairedEntity .

        VALUES ?pairedEntity {
            <https://dlnarratives.eu/resource/Q43059> 
            <https://dlnarratives.eu/resource/Q283>   
            <https://dlnarratives.eu/resource/Q1313> 
        }
    }
}
order by ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"Murauer Beer","https://dlnarratives.eu/narrative/N132_VC_08_AT","Austria"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Mölltal-Glockner Lamm (engl. lamb)","https://dlnarratives.eu/narrative/N223_VC_13_AT","Austria"
"Murbodner Beef","https://dlnarratives.eu/narrative/N239_VC_14_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Bio vom Berg (translated: Organic products from mountain areas)","https://dlnarratives.eu/narrative/N326_VC_19_AT","Austria"
"ALL SEASON MOUNTAIN TOURISM (BANSKO RESORT)","https://dlnarratives.eu/narrative/N169_VC_10_BG","Bulgaria"
"Sheep wool","https://dlnarratives.eu/narrative/N226_VC_13_CZ","Czech Republic"
"Beer - local minibreweries","https://dlnarratives.eu/narrative/N243_VC_14_CZ","Czech Republic"
"High Quality Beef Production","https://dlnarratives.eu/narrative/N26_VC_02_CZ","Czech Republic"
"Smoked meat and sausages","https://dlnarratives.eu/narrative/N312_VC_18_CZ","Czech Republic"
"Goats - dairy products","https://dlnarratives.eu/narrative/N62_VC_04_CZ","Czech Republic"
"Wood for energy production","https://dlnarratives.eu/narrative/N8_VC_20_CZ","Czech Republic"
"Dry cold meats NON -PDO, Ficatelli, sausages and pieces","https://dlnarratives.eu/narrative/N101_VC_06_FR","France"
"Huile d'olive de Corse - Oliu d'Alivu-PDO","https://dlnarratives.eu/narrative/N10_VC_20_FR","France"
"honey - Miels de Corse-Mele di Corsica - PDO","https://dlnarratives.eu/narrative/N119_VC_07_FR","France"
"Picodon AOP","https://dlnarratives.eu/narrative/N120_VC_07_FR1","France"
"Unedo arbutus honey-PDO","https://dlnarratives.eu/narrative/N138_VC_08_FR","France"
"Corsican Milk Kid","https://dlnarratives.eu/narrative/N210_VC_12_FR","France"
"Poultry farming, integration by the cooperative","https://dlnarratives.eu/narrative/N211_VC_12_FR1","France"
"Extensive and pastoral livestock farming","https://dlnarratives.eu/narrative/N29_VC_02_FR1","France"
"On-farm processed cheeses based on pastoral breeding and sponteneous resources","https://dlnarratives.eu/narrative/N46_VC_03_FR","France"
"Market gardening, diversified with organic value chain and local channels distribution","https://dlnarratives.eu/narrative/N47_VC_03_FR1","France"
"Dairy Cheese","https://dlnarratives.eu/narrative/N64_VC_04_FR","France"
"Diversification strategies of conventional agriculture in large irrigated field crops","https://dlnarratives.eu/narrative/N9_VC_01_FR1","France"
"Activities connected to PDO olive oil products of designated origin and geographical indication","https://dlnarratives.eu/narrative/N121_VC_07_GR","Greece"
"Activities related to carobs, carob products and by-products in the area of Selli, Rethymno","https://dlnarratives.eu/narrative/N140_VC_08_GR","Greece"
"Prickly pear cactus is a perennial shrub that grows everywhere in the dry, rocky terrain of Dicti and in the region of Viannos","https://dlnarratives.eu/narrative/N246_VC_14_GR","Greece"
"Traditional potato from Olaszfalu","https://dlnarratives.eu/narrative/N160_VC_09_HU","Hungary"
"Traditional cabbeges from Olaszfalu","https://dlnarratives.eu/narrative/N176_VC_10_HU","Hungary"
"Logging, timber production, afforestation, maintenance, leasure, sport","https://dlnarratives.eu/narrative/N194_VC_11_HU","Hungary"
"Ecological garden in Pallagvölgy - a joint enterprise","https://dlnarratives.eu/narrative/N316_VC_18_HU","Hungary"
"The tastes of Monostor","https://dlnarratives.eu/narrative/N333_VC_19_HU","Hungary"
"Sustainable local food system project - Kóspallag","https://dlnarratives.eu/narrative/N33_VC_22_HU","Hungary"
"Pumpkin seed products in Boldva","https://dlnarratives.eu/narrative/N67_VC_04_HU","Hungary"
"The beekeeper produces organic honeys, honey aged in barrels and mead (fermented honey drink)","https://dlnarratives.eu/narrative/N248_VC_14_IT","Italy"
"The farm sustainably produces apple and derived alcoholic beverages (cider)","https://dlnarratives.eu/narrative/N57_VC_27_IT","Italy"
"Fish farming - PDO (Protected Designation of Origin) Rakfish from Valdres","https://dlnarratives.eu/narrative/N199_VC_11_SCA","Norway"
"Fly fishing in Hemsedal","https://dlnarratives.eu/narrative/N218_VC_12_SCA","Norway"
"DO Douro Wine - Douro Superior - Vila Nova de Foz Côa","https://dlnarratives.eu/narrative/N14_VC_01_PT","Portugal"
"Tourism based on thermal water from thermal srings at Serra da Estrela","https://dlnarratives.eu/narrative/N319_VC_18_PT2","Portugal"
"FARMED and PROCESSED MOUNTAIN TROUT","https://dlnarratives.eu/narrative/N15_VC_20_RO","Romania"
"CERTIFIED ECOTOURISM","https://dlnarratives.eu/narrative/N53_VC_03_RO","Romania"
"Vaccinium Myrtillus from National Park Kopaonik. Wild blue berry picking and processing","https://dlnarratives.eu/narrative/N110_VC_06_SER","Serbia"
"Trout fish produced in clear and fresh water ponds","https://dlnarratives.eu/narrative/N128_VC_07_SER","Serbia"
"Tourism /one of the main activities in mountain regions; Zlatibor mountain as one VC","https://dlnarratives.eu/narrative/N147_VC_08_SER","Serbia"
"Raspberries produced in the area of Arilje region (western Serbia), PDO","https://dlnarratives.eu/narrative/N253_VC_14_SER","Serbia"
"Berries cultivated in Kopaonik Mountain/ National park, Dinaric Alps region; other mountain areas","https://dlnarratives.eu/narrative/N304_VC_17_SER","Serbia"
"Wild mushrooms collected in the mountainous area of Kopaonik mountain","https://dlnarratives.eu/narrative/N322_VC_18_SER","Serbia"
"Spring and mineral waters from mountains - sold as natural or flavored with local wild herbs","https://dlnarratives.eu/narrative/N323_VC_18_SK","Slovakia"
"Goat's milk cheese made by hand directly on the mountain pasture","https://dlnarratives.eu/narrative/N113_VC_51_CH2","Switzerland"
"Label of the Swiss Mother Cow Association (association des vaches mères suisse) for beef from suckler cows or suckler cows in accordance with the principles of organic farming","https://dlnarratives.eu/narrative/N116_VC_54_CH2","Switzerland"
"PDO cheese made from raw and whole cow's milk. It's consumption is unique in that it is eaten in the form of rosettes obtained with the help of the 'girolle'","https://dlnarratives.eu/narrative/N135_VC_08_CH2","Switzerland"
"Gruyère is a typical Swiss cheese, produced in the mountain pastures in summer","https://dlnarratives.eu/narrative/N154_VC_09_CH2","Switzerland"
"Beer","https://dlnarratives.eu/narrative/N170_VC_10_CH1","Switzerland"
"'GranAlpin' cereal products","https://dlnarratives.eu/narrative/N206_VC_12_CH1","Switzerland"
"alpine pasture cheese with organic sheep's milk","https://dlnarratives.eu/narrative/N207_VC_12_CH2","Switzerland"
"It is the only remaining horse breed of Swiss origin specialised in driving but with a versatile profile (trekking, sport)","https://dlnarratives.eu/narrative/N242_VC_14_CH2","Switzerland"
"Timber harvesting","https://dlnarratives.eu/narrative/N260_VC_15_CH2","Switzerland"
"Trout farming in the Doubs river","https://dlnarratives.eu/narrative/N278_VC_16_CH2","Switzerland"
"Wine","https://dlnarratives.eu/narrative/N61_VC_04_CH1","Switzerland"
"Traditional alpine herbs","https://dlnarratives.eu/narrative/N79_VC_05_CH1","Switzerland"
"Cheese made from cow's milk, eaten raw and baked","https://dlnarratives.eu/narrative/N94_VC_43_CH2","Switzerland"
"Jogitz yogurt made from Goat's milk (several flavours)","https://dlnarratives.eu/narrative/N98_VC_06_CH2","Switzerland"
"cow's milk milk that has not been pasteurised, sterilised or thermised and is sold directly","https://dlnarratives.eu/narrative/N99_VC_45_CH2","Switzerland"
"Special kind of tulum cheese from goat and sheep milk","https://dlnarratives.eu/narrative/N167_VC_09_TR","Turkey"
"Value Chain","https://dlnarratives.eu/narrative/N19_VC_20_TR","Turkey"
"Aydın Fig is an agriicultural product that is consumed fresh and dried","https://dlnarratives.eu/narrative/N273_VC_15_TR","Turkey"
"Fresh Fruit (Apple)","https://dlnarratives.eu/narrative/N75_VC_04_TR","Turkey"
"Highland Spring and Tesco Perthshire Mineral water is based on extracting groundwater from hills","https://dlnarratives.eu/narrative/N22_VC_01_UK","United Kingdom"
"Hydropower - upland river lakes for renewable energy through pumped storage (Electric mountain)","https://dlnarratives.eu/narrative/N307_VC_17_UK","United Kingdom"
"Scotch whisky (PDO) which rely on clean water, peat and barley. Also an important tourist attraction","https://dlnarratives.eu/narrative/N4_VC_19_UK","United Kingdom"
```

### Q2. VCs that include innovations related to marketing strategies

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 


SELECT DISTINCT ?title ?narrative ?CountryName


WHERE {

    ?narrative rdfs:label ?title ;
              narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    ?event1 narra:partOfNarrative ?narrative ;
            narra:hasEntity ?mandatoryEntity .

    VALUES ?mandatoryEntity {
        <https://dlnarratives.eu/resource/Q39809> 
        <https://dlnarratives.eu/resource/Q1363963> 
    }


}
orderby ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"Holzwelt Murau (transl.: World of wood Murau)","https://dlnarratives.eu/narrative/N114_VC_07_AT","Austria"
"Murauer Beer","https://dlnarratives.eu/narrative/N132_VC_08_AT","Austria"
"Murauer Heumilch (transl.: hay milk)","https://dlnarratives.eu/narrative/N151_VC_09_AT","Austria"
"Osttiroler Mountain Lamb ('Osttiroler Berglamm')","https://dlnarratives.eu/narrative/N168_VC_10_AT","Austria"
"Murbodner Potatoes ('Murbodner Erdäpfel')","https://dlnarratives.eu/narrative/N185_VC_11_AT","Austria"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Lungauer Eachtling","https://dlnarratives.eu/narrative/N204_VC_12_AT","Austria"
"Mölltal-Glockner Lamm (engl. lamb)","https://dlnarratives.eu/narrative/N223_VC_13_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Sheep farmers from Weiz ('Weizer Schafbauern')","https://dlnarratives.eu/narrative/N257_VC_15_AT","Austria"
"Kärntna Låxn","https://dlnarratives.eu/narrative/N275_VC_16_AT","Austria"
"Ausseerland Seesaibling und Forelle (engl. artic char and trout from the Region Ausseerland)","https://dlnarratives.eu/narrative/N291_VC_17_AT","Austria"
"Murtaler pumpkin seed oil ('Kürbiskernöl')","https://dlnarratives.eu/narrative/N308_VC_18_AT","Austria"
"Bio vom Berg (translated: Organic products from mountain areas)","https://dlnarratives.eu/narrative/N326_VC_19_AT","Austria"
"Almenland Herbs","https://dlnarratives.eu/narrative/N41_VC_03_AT","Austria"
"Almenland Fish","https://dlnarratives.eu/narrative/N59_VC_04_AT","Austria"
"Styria Beef","https://dlnarratives.eu/narrative/N5_VC_20_AT","Austria"
"Almenland Honey","https://dlnarratives.eu/narrative/N77_VC_05_AT","Austria"
"Almenland Schnaps","https://dlnarratives.eu/narrative/N95_VC_06_AT","Austria"
"'LINBUL FARM' (ONLINE SALES OF GRASS-FED BEEF)","https://dlnarratives.eu/narrative/N115_VC_07_BG","Bulgaria"
"'FOOD FROM THE MOUNTAIN' FARMERS' ASSOCIATION","https://dlnarratives.eu/narrative/N152_VC_09_BG","Bulgaria"
"ALL SEASON MOUNTAIN TOURISM (BANSKO RESORT)","https://dlnarratives.eu/narrative/N169_VC_10_BG","Bulgaria"
"'MEADOWS IN THE MOUNTAINS' FESTIVAL","https://dlnarratives.eu/narrative/N205_VC_12_BG","Bulgaria"
"CULINARY GUIDEBOOK TO THE EASTERN RHODOPES","https://dlnarratives.eu/narrative/N6_VC_20_BG","Bulgaria"
"Marmelade","https://dlnarratives.eu/narrative/N136_VC_08_CZ","Czech Republic"
"Valašský frgál","https://dlnarratives.eu/narrative/N155_VC_09_CZ","Czech Republic"
"Herb liquers","https://dlnarratives.eu/narrative/N171_VC_10_CZ","Czech Republic"
"Bee products","https://dlnarratives.eu/narrative/N189_VC_11_CZ","Czech Republic"
"Fruit spirit (hard liquer)","https://dlnarratives.eu/narrative/N208_VC_12_CZ","Czech Republic"
"Sheep wool","https://dlnarratives.eu/narrative/N226_VC_13_CZ","Czech Republic"
"Beer - local minibreweries","https://dlnarratives.eu/narrative/N243_VC_14_CZ","Czech Republic"
"Syrups","https://dlnarratives.eu/narrative/N261_VC_15_CZ","Czech Republic"
"High Quality Beef Production","https://dlnarratives.eu/narrative/N26_VC_02_CZ","Czech Republic"
"Game meat selling to local consumers","https://dlnarratives.eu/narrative/N295_VC_17_CZ","Czech Republic"
"Smoked meat and sausages","https://dlnarratives.eu/narrative/N312_VC_18_CZ","Czech Republic"
"Cow - dairy products","https://dlnarratives.eu/narrative/N44_VC_03_CZ","Czech Republic"
"Mineral water","https://dlnarratives.eu/narrative/N5_VC_01_CZ","Czech Republic"
"Goats - dairy products","https://dlnarratives.eu/narrative/N62_VC_04_CZ","Czech Republic"
"Sheep - dairy products","https://dlnarratives.eu/narrative/N81_VC_05_CZ","Czech Republic"
"Dry cold meats NON -PDO, Ficatelli, sausages and pieces","https://dlnarratives.eu/narrative/N101_VC_06_FR","France"
"honey - Miels de Corse-Mele di Corsica - PDO","https://dlnarratives.eu/narrative/N119_VC_07_FR","France"
"Unedo arbutus honey-PDO","https://dlnarratives.eu/narrative/N138_VC_08_FR","France"
"'Corsican chestnut flour - Farina castagnina corsa' PDO","https://dlnarratives.eu/narrative/N157_VC_09_FR","France"
"Agriturism - turistic and pedagogic welcome in farms","https://dlnarratives.eu/narrative/N158_VC_09_FR1","France"
"Corsican aromatic plant : Immortelle de Corse - 'hélichrysum italicum ssp italicum'","https://dlnarratives.eu/narrative/N173_VC_10_FR","France"
"Organic still wine production","https://dlnarratives.eu/narrative/N174_VC_10_FR1","France"
"Poultry farming, integration by the cooperative","https://dlnarratives.eu/narrative/N211_VC_12_FR1","France"
"Extensive and pastoral livestock farming","https://dlnarratives.eu/narrative/N29_VC_02_FR1","France"
"Mountain apple production","https://dlnarratives.eu/narrative/N314_VC_18_FR","France"
"Perivillage production - vegetable and fruit","https://dlnarratives.eu/narrative/N331_VC_19_FR","France"
"Market gardening, diversified with organic value chain and local channels distribution","https://dlnarratives.eu/narrative/N47_VC_03_FR1","France"
"Clairette de Die - Wine production targeted towards export","https://dlnarratives.eu/narrative/N65_VC_04_FR1","France"
"PDO-Dry cured pork","https://dlnarratives.eu/narrative/N83_VC_05_FR","France"
"Production of Graviera cheese (Gruyere) in the area Amari, on the western slopes of Psiloritis mountain","https://dlnarratives.eu/narrative/N10_VC_01_GR","Greece"
"Activites for the production and handling of PGI potatoes","https://dlnarratives.eu/narrative/N11_VC_20_GR","Greece"
"Activities based on chestnuts from the mountainous areas of the western most part of Crete","https://dlnarratives.eu/narrative/N175_VC_10_GR","Greece"
"Apicature activities in mountainous areas of Kissamos, Chania","https://dlnarratives.eu/narrative/N193_VC_11_GR","Greece"
"Processing of milk and wheat for the production of traditional frumenty in the mountainous area of Anogeia","https://dlnarratives.eu/narrative/N25_VC_21_GR","Greece"
"Activities connected to legumes, traditinally cultivated in Feneos area in the Peloponnese","https://dlnarratives.eu/narrative/N297_VC_17_GR","Greece"
"Epirus Feta is PDO cheese made of sheep and goat milk in the Epirus region, Ammotopos Arta","https://dlnarratives.eu/narrative/N332_VC_19_GR","Greece"
"Production of traditional edible products from the mountainous area of Gergeri, Fortyna (Psiloritis mountain)","https://dlnarratives.eu/narrative/N66_VC_04_GR","Greece"
"Activities connected to the production of wine in the PDO Peza zone, at eastern Crete","https://dlnarratives.eu/narrative/N85_VC_05_GR","Greece"
"Biscuits and chocolate from Pannonhalma","https://dlnarratives.eu/narrative/N122_VC_07_HU","Hungary"
"Ranger - truffle hunting","https://dlnarratives.eu/narrative/N12_VC_20_HU","Hungary"
"Cosmetics, health products and herbal tea in Pannonhalma","https://dlnarratives.eu/narrative/N141_VC_08_HU","Hungary"
"Traditional potato from Olaszfalu","https://dlnarratives.eu/narrative/N160_VC_09_HU","Hungary"
"Traditional cabbeges from Olaszfalu","https://dlnarratives.eu/narrative/N176_VC_10_HU","Hungary"
"Skiing and downhill cycling in Eplény","https://dlnarratives.eu/narrative/N230_VC_13_HU","Hungary"
"Honey from the Bakony mountains","https://dlnarratives.eu/narrative/N247_VC_14_HU","Hungary"
"Organic garlic from Olaszfalu","https://dlnarratives.eu/narrative/N265_VC_15_HU","Hungary"
"Ecological garden in Pallagvölgy - a joint enterprise","https://dlnarratives.eu/narrative/N316_VC_18_HU","Hungary"
"Gastro village Köveskál Eat the View","https://dlnarratives.eu/narrative/N31_VC_02_HU","Hungary"
"The tastes of Monostor","https://dlnarratives.eu/narrative/N333_VC_19_HU","Hungary"
"Nivegy-valley artisan chese makers","https://dlnarratives.eu/narrative/N39_VC_23_HU","Hungary"
"Nivegy-valley small family wineries","https://dlnarratives.eu/narrative/N49_VC_03_HU","Hungary"
"Pumpkin seed products in Boldva","https://dlnarratives.eu/narrative/N67_VC_04_HU","Hungary"
"Trizs, the fruitful village","https://dlnarratives.eu/narrative/N86_VC_05_HU","Hungary"
"Biodistretto Val di Vara (Organic district in Val di Vara)","https://dlnarratives.eu/narrative/N101_VC_45_IT","Italy"
"Through a ICT solution is established an interactive costumers participation in farm production","https://dlnarratives.eu/narrative/N105_VC_06_IT","Italy"
"With a open-air grazing in a regional natural park, it is produced and processed high-quality milk","https://dlnarratives.eu/narrative/N107_VC_48_IT","Italy"
"Bovine meat of race Limousine and flour from corn 'ottofile'","https://dlnarratives.eu/narrative/N114_VC_52_IT","Italy"
"This farm grows and commercializes herbs and with garlic produced mountain area","https://dlnarratives.eu/narrative/N195_VC_11_IT","Italy"
"This farm produces high quality certified milk and cheese in a mountain nature park","https://dlnarratives.eu/narrative/N231_VC_13_IT","Italy"
"The beekeeper produces organic honeys, honey aged in barrels and mead (fermented honey drink)","https://dlnarratives.eu/narrative/N248_VC_14_IT","Italy"
"Pontepietra is a small organic farm that cultivates medicinal and aromatic herbs and practices the collection of wild herbs","https://dlnarratives.eu/narrative/N27_VC_21_IT","Italy"
"Beekeeping and the Amatrice honey certified","https://dlnarratives.eu/narrative/N299_VC_17_IT","Italy"
"The rural butchery","https://dlnarratives.eu/narrative/N317_VC_18_IT","Italy"
"Chestnut flour coming from the rediscovered chesnut cultivation activities in the area","https://dlnarratives.eu/narrative/N32_VC_02_IT","Italy"
"Old varieties of potatos (Walser Kartoffeln)","https://dlnarratives.eu/narrative/N34_VC_22_IT","Italy"
"Alto Trentino, organic wine production for premium wines","https://dlnarratives.eu/narrative/N40_VC_23_IT","Italy"
"The farm sustainably produces apple and derived alcoholic beverages (cider)","https://dlnarratives.eu/narrative/N57_VC_27_IT","Italy"
"Island Wine from Lipari, Aeolian Islands","https://dlnarratives.eu/narrative/N66_VC_29_IT","Italy"
"Cultivation and processing of organic apple on abandoned lands","https://dlnarratives.eu/narrative/N68_VC_04_IT","Italy"
"Volcanic Wine (Sicily-Etna)","https://dlnarratives.eu/narrative/N69_VC_30_IT","Italy"
"Different varieties of potatos produce under specific rules","https://dlnarratives.eu/narrative/N71_VC_31_IT","Italy"
"Grapevine on terraces- Valtellina","https://dlnarratives.eu/narrative/N77_VC_34_IT","Italy"
"'Di Iorio' is a old (by 1750) artisanal confectionery in Southern Italy specialized in nougat production","https://dlnarratives.eu/narrative/N79_VC_35_IT","Italy"
"Caciocavallo cheese form southern Dauni mountains","https://dlnarratives.eu/narrative/N81_VC_36_IT","Italy"
"Central Appenninian (Abruzzo)","https://dlnarratives.eu/narrative/N83_VC_37_IT","Italy"
"Di Nucci renews the tradition of cheese production, supporting breeders and sustainable livestock","https://dlnarratives.eu/narrative/N87_VC_05_IT","Italy"
"La Cattedra- Commons farming for social and educational purposes- Asiago Plateau","https://dlnarratives.eu/narrative/N93_VC_42_IT","Italy"
"Potatos","https://dlnarratives.eu/narrative/N143_VC_08_NMK","North Macedonia"
"Rural tourism in Maleshevija (North Macedonia) - Mountain related tourism","https://dlnarratives.eu/narrative/N249_VC_14_NMK","North Macedonia"
"Kozhuvchanka - Drinking water produced from a spring in a Kozhuv Mountain","https://dlnarratives.eu/narrative/N284_VC_16_NMK","North Macedonia"
"Wine - Vranec","https://dlnarratives.eu/narrative/N318_VC_18_NMK","North Macedonia"
"Honey in Maleshevija (North Macedonia)","https://dlnarratives.eu/narrative/N33_VC_02_NMK","North Macedonia"
"Mushrooms - NTFP","https://dlnarratives.eu/narrative/N51_VC_03_NMK","North Macedonia"
"Medicinal herbs - NTFP","https://dlnarratives.eu/narrative/N88_VC_05_NMK","North Macedonia"
"Organic primitive grain muesli","https://dlnarratives.eu/narrative/N109_VC_06_SCA","Norway"
"Barley flour and groats","https://dlnarratives.eu/narrative/N127_VC_07_SCA","Norway"
"PGI (Protected Geographical Indication) Goat cheese / Undredal Brown Cheese","https://dlnarratives.eu/narrative/N164_VC_09_SCA","Norway"
"Artisanal beer from Stjordal","https://dlnarratives.eu/narrative/N17_VC_01_SCA","Norway"
"Fish farming - PDO (Protected Designation of Origin) Rakfish from Valdres","https://dlnarratives.eu/narrative/N199_VC_11_SCA","Norway"
"Unsprayed berries","https://dlnarratives.eu/narrative/N235_VC_13_SCA","Norway"
"Nature-based Tourism","https://dlnarratives.eu/narrative/N287_VC_16_SCA","Norway"
"Sami Handicraft - Duodji","https://dlnarratives.eu/narrative/N321_VC_18_SCA","Norway"
"PGI (Protected Geographical Indication) Cider from Hardanger","https://dlnarratives.eu/narrative/N36_VC_02_SCA","Norway"
"Meat products (dry and cured meat)","https://dlnarratives.eu/narrative/N72_VC_04_SCA","Norway"
"Free ranged animals","https://dlnarratives.eu/narrative/N91_VC_05_SCA","Norway"
"'MOZZARELLA'","https://dlnarratives.eu/narrative/N217_VC_12_RO","Romania"
"'SALAM MONTAN ANGUS'","https://dlnarratives.eu/narrative/N269_VC_15_RO","Romania"
"'GASTRO LOCAL'","https://dlnarratives.eu/narrative/N320_VC_18_RO","Romania"
"Vaccinium Myrtillus from National Park Kopaonik. Wild blue berry picking and processing","https://dlnarratives.eu/narrative/N110_VC_06_SER","Serbia"
"Vlasina honey PDO","https://dlnarratives.eu/narrative/N165_VC_09_SER","Serbia"
"Beef fresh meat (whole animals), dry meat part of the same VCs (on-farm or small processing units)","https://dlnarratives.eu/narrative/N17_VC_20_SER","Serbia"
"Pirot hard cheese from sheep and cow milk","https://dlnarratives.eu/narrative/N181_VC_10_SER","Serbia"
"White cheese in brine, produced of mixed milk - cow and sheep milk","https://dlnarratives.eu/narrative/N200_VC_11_SER","Serbia"
"Raspberries produced in the area of Arilje region (western Serbia), PDO","https://dlnarratives.eu/narrative/N253_VC_14_SER","Serbia"
"Berries cultivated in Kopaonik Mountain/ National park, Dinaric Alps region; other mountain areas","https://dlnarratives.eu/narrative/N304_VC_17_SER","Serbia"
"Wild mushrooms collected in the mountainous area of Kopaonik mountain","https://dlnarratives.eu/narrative/N322_VC_18_SER","Serbia"
"Wool handmade carpets with traditional motives","https://dlnarratives.eu/narrative/N49_VC_25_SER","Serbia"
"Dried saussage made of localy produced sheep, goat, beef or donkey meat mixture, PDO application","https://dlnarratives.eu/narrative/N58_VC_27_SER","Serbia"
"Potatoes grown in high altitudes, known by their quality","https://dlnarratives.eu/narrative/N73_VC_04_SER","Serbia"
"Family farms with both production and recreational possibilities for visitors","https://dlnarratives.eu/narrative/N111_VC_06_SK","Slovakia"
"Small scale production of high quality oils for eating and cosmetic purposes","https://dlnarratives.eu/narrative/N148_VC_08_SK","Slovakia"
"The labelled traditional cheese products from sheep or from a mixture of sheep and cow milk","https://dlnarratives.eu/narrative/N19_VC_01_SK","Slovakia"
"E-commerce and governement incentives to support domestic game meat consumption","https://dlnarratives.eu/narrative/N236_VC_13_SK","Slovakia"
"Beekeeping in mountain areas for different bee products and polination","https://dlnarratives.eu/narrative/N272_VC_15_SK","Slovakia"
"A combination of beekeeping and agrotourism","https://dlnarratives.eu/narrative/N288_VC_16_SK","Slovakia"
"Winter ski recreation in Slovak mountains","https://dlnarratives.eu/narrative/N2_VC_19_SK","Slovakia"
"Spring and mineral waters from mountains - sold as natural or flavored with local wild herbs","https://dlnarratives.eu/narrative/N323_VC_18_SK","Slovakia"
"Lamb meat production - focusing on local consumers instead of export","https://dlnarratives.eu/narrative/N56_VC_03_SK","Slovakia"
"Sheep's wool processing helps maintain traditional sheep farming in Slovakia; the sheep wool products include home textile products and clothes","https://dlnarratives.eu/narrative/N74_VC_04_SK","Slovakia"
"Wines of the Contraviesa","https://dlnarratives.eu/narrative/N100_VC_06_ES","Spain"
"Quince juice and vinegar from Carcabuey and Priego de Córdoba","https://dlnarratives.eu/narrative/N100_VC_45_ES","Spain"
"Leather handcraft","https://dlnarratives.eu/narrative/N104_VC_47_ES","Spain"
"Mycology in the mountains of Soria","https://dlnarratives.eu/narrative/N111_VC_50_ES","Spain"
"Honey","https://dlnarratives.eu/narrative/N156_VC_09_ES","Spain"
"Olive oil Protected Designation of Origin Sierra de Segura","https://dlnarratives.eu/narrative/N172_VC_10_ES","Spain"
"Organic Olive Oil from Zuheros","https://dlnarratives.eu/narrative/N227_VC_13_ES","Spain"
"Euskal Txerria Pig","https://dlnarratives.eu/narrative/N23_VC_21_ES","Spain"
"Cheese from Picos de Europa","https://dlnarratives.eu/narrative/N262_VC_15_ES","Spain"
"Spanish fighting bull (Toro de Lidia)","https://dlnarratives.eu/narrative/N296_VC_17_ES","Spain"
"Spirits - anis and cherry drinks","https://dlnarratives.eu/narrative/N45_VC_03_ES","Spain"
"Meat from Cervera de Pisuerga and the Palencia Mountains","https://dlnarratives.eu/narrative/N47_VC_25_ES","Spain"
"Veal from Cantabria","https://dlnarratives.eu/narrative/N52_VC_26_ES","Spain"
"Botillo from El Bierzo","https://dlnarratives.eu/narrative/N61_VC_28_ES","Spain"
"Lamb production - COVAP (Cooperativa Ganadera del Valle de los Pedroches)","https://dlnarratives.eu/narrative/N68_VC_30_ES","Spain"
"Suckling goat from Malaga","https://dlnarratives.eu/narrative/N70_VC_31_ES","Spain"
"Indigenous pulses, cereals and fruits of Ascara (Jaca)","https://dlnarratives.eu/narrative/N78_VC_35_ES","Spain"
"Sustainable rural tourism","https://dlnarratives.eu/narrative/N7_VC_01_ES","Spain"
"Production of spirits, liquors, and brandies, following traditional recipes","https://dlnarratives.eu/narrative/N82_VC_05_ES","Spain"
"Cherries from Castillo de Locubín","https://dlnarratives.eu/narrative/N85_VC_38_ES","Spain"
"Grazalema wool blankets, textile craftsmanship","https://dlnarratives.eu/narrative/N86_VC_39_ES","Spain"
"Chestnut products","https://dlnarratives.eu/narrative/N88_VC_40_ES","Spain"
"Subbética Ecológica, production and consumption of organic food","https://dlnarratives.eu/narrative/N92_VC_42_ES","Spain"
"Mantecados de Rute, Traditional Christmas cakes made mainly from lard","https://dlnarratives.eu/narrative/N97_VC_44_ES","Spain"
"Beer","https://dlnarratives.eu/narrative/N170_VC_10_CH1","Switzerland"
"'GranAlpin' cereal products","https://dlnarratives.eu/narrative/N206_VC_12_CH1","Switzerland"
"It is the only remaining horse breed of Swiss origin specialised in driving but with a versatile profile (trekking, sport)","https://dlnarratives.eu/narrative/N242_VC_14_CH2","Switzerland"
"'Soglio' cosmetics","https://dlnarratives.eu/narrative/N259_VC_15_CH1","Switzerland"
"'Patrimont Switzerland' saving old breeds","https://dlnarratives.eu/narrative/N277_VC_16_CH1","Switzerland"
"Touristic services","https://dlnarratives.eu/narrative/N310_VC_18_CH1","Switzerland"
"Saffron","https://dlnarratives.eu/narrative/N328_VC_19_CH1","Switzerland"
"'Fruttipertutti'","https://dlnarratives.eu/narrative/N37_VC_23_CH1","Switzerland"
"'ZOJA' apple tasting box","https://dlnarratives.eu/narrative/N43_VC_24_CH1","Switzerland"
"'AlpenPionier' hemp products","https://dlnarratives.eu/narrative/N46_VC_25_CH1","Switzerland"
"'Alpinavera' Nuts","https://dlnarratives.eu/narrative/N51_VC_26_CH1","Switzerland"
"'AlpenHirt' dried meat","https://dlnarratives.eu/narrative/N60_VC_28_CH1","Switzerland"
"Alps Art Academy and Art Safiental","https://dlnarratives.eu/narrative/N67_VC_30_CH1","Switzerland"
"Promotion and development of horse meat from the swiss jura","https://dlnarratives.eu/narrative/N80_VC_05_CH2","Switzerland"
"cow's milk milk that has not been pasteurised, sterilised or thermised and is sold directly","https://dlnarratives.eu/narrative/N99_VC_45_CH2","Switzerland"
"Special types of chestnut trees for candied which is at the east of the İzmir on Bozdağ Mountains","https://dlnarratives.eu/narrative/N149_VC_08_TR","Turkey"
"Special kind of tulum cheese from goat and sheep milk","https://dlnarratives.eu/narrative/N167_VC_09_TR","Turkey"
"Pine Nuts","https://dlnarratives.eu/narrative/N306_VC_17_TR","Turkey"
"It is a traditional local cheese from goat and sheep milk","https://dlnarratives.eu/narrative/N324_VC_18_TR","Turkey"
"Value Chain","https://dlnarratives.eu/narrative/N3_VC_19_TR","Turkey"
"Highland cows - bred for genes and cultural status not necessarily for meat/dairy- Lochaber","https://dlnarratives.eu/narrative/N256_VC_14_UK","United Kingdom"
"Jewellery made from heather (Perth and Kinross LAU1)","https://dlnarratives.eu/narrative/N274_VC_15_UK","United Kingdom"
"Independent and guided visits motivated by visiting wild places and seeing wildlife","https://dlnarratives.eu/narrative/N30_VC_21_UK","United Kingdom"
"Mountain region hard white cows milk cheese with PGI from Caerphillly LAU1 region within Wales","https://dlnarratives.eu/narrative/N40_VC_02_UK","United Kingdom"
"Scottish Heather honey- heather that is marketed as produced from heather moorland","https://dlnarratives.eu/narrative/N58_VC_03_UK","United Kingdom"
```

### Q3. VCs whose reference mountain range is composed of limestone

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 

SELECT DISTINCT ?title ?narrative  ?CountryName

WHERE {

    ?narrative rdfs:label ?title ;
              narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    ?event1 narra:partOfNarrative ?narrative ;
            narra:hasEntity ?mandatoryEntity .

    VALUES ?mandatoryEntity {
        <https://dlnarratives.eu/resource/Q23757> 
    }


}
orderby ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"Osttiroler Mountain Lamb ('Osttiroler Berglamm')","https://dlnarratives.eu/narrative/N168_VC_10_AT","Austria"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Sheep farmers from Weiz ('Weizer Schafbauern')","https://dlnarratives.eu/narrative/N257_VC_15_AT","Austria"
"Almenland Herbs","https://dlnarratives.eu/narrative/N41_VC_03_AT","Austria"
"Almenland Fish","https://dlnarratives.eu/narrative/N59_VC_04_AT","Austria"
"Almenland Honey","https://dlnarratives.eu/narrative/N77_VC_05_AT","Austria"
"Almenland Schnaps","https://dlnarratives.eu/narrative/N95_VC_06_AT","Austria"
"'ISKAR GORGE' DAY TRIPS","https://dlnarratives.eu/narrative/N224_VC_13_BG","Bulgaria"
"Dittany grows widely and is cultivated in Embaros, Dicti mountain area","https://dlnarratives.eu/narrative/N229_VC_13_GR","Greece"
"Prickly pear cactus is a perennial shrub that grows everywhere in the dry, rocky terrain of Dicti and in the region of Viannos","https://dlnarratives.eu/narrative/N246_VC_14_GR","Greece"
"CERTIFIED ECOTOURISM","https://dlnarratives.eu/narrative/N53_VC_03_RO","Romania"
"Absinthe is a set of spirits made from absinth plants, to be mixed with water for consumption","https://dlnarratives.eu/narrative/N108_VC_49_CH2","Switzerland"
"Goat's milk cheese made by hand directly on the mountain pasture","https://dlnarratives.eu/narrative/N113_VC_51_CH2","Switzerland"
"PDO cheese made from raw and whole cow's milk. It's consumption is unique in that it is eaten in the form of rosettes obtained with the help of the 'girolle'","https://dlnarratives.eu/narrative/N135_VC_08_CH2","Switzerland"
"Salted cake made of leavened dough and covered with a cream-based mixture","https://dlnarratives.eu/narrative/N188_VC_11_CH2","Switzerland"
"alpine pasture cheese with organic sheep's milk","https://dlnarratives.eu/narrative/N207_VC_12_CH2","Switzerland"
"It is the only remaining horse breed of Swiss origin specialised in driving but with a versatile profile (trekking, sport)","https://dlnarratives.eu/narrative/N242_VC_14_CH2","Switzerland"
"Trout farming in the Doubs river","https://dlnarratives.eu/narrative/N278_VC_16_CH2","Switzerland"
"Local mustard produces and transformed on the farm","https://dlnarratives.eu/narrative/N311_VC_18_CH2","Switzerland"
"Cooperative that produces malt from local barley","https://dlnarratives.eu/narrative/N4_VC_01_CH2","Switzerland"
"Promotion and development of horse meat from the swiss jura","https://dlnarratives.eu/narrative/N80_VC_05_CH2","Switzerland"
"Different types of honey from the Jura Vaudois (moutain, flower, fir tree, forest). The beehives are in winter at l'Isle in the plains and in summer in the mountains","https://dlnarratives.eu/narrative/N84_VC_38_CH2","Switzerland"
"Plants grown, dried and transformed into herbal tea or cosmetic products directly on the farm","https://dlnarratives.eu/narrative/N90_VC_41_CH2","Switzerland"
"Cheese made from cow's milk, eaten raw and baked","https://dlnarratives.eu/narrative/N94_VC_43_CH2","Switzerland"
"Jogitz yogurt made from Goat's milk (several flavours)","https://dlnarratives.eu/narrative/N98_VC_06_CH2","Switzerland"
```

### Q4. VCs whose reference mountain range is the Alps

#### SPARQL query

```sparql
PREFIX narra:  <https://dlnarratives.eu/ontology#> 
PREFIX crm:    <http://erlangen-crm.org/current/> 
PREFIX rdfs:  <http://www.w3.org/2000/01/rdf-schema#> 

SELECT DISTINCT ?title ?narrative  ?CountryName

WHERE {

    ?narrative rdfs:label ?title ;
              narra:isAboutCountry ?country .
  	?country rdfs:label ?CountryName .

    ?event1 narra:partOfNarrative ?narrative ;
            narra:hasEntity ?mandatoryEntity .

    VALUES ?mandatoryEntity {
        <https://dlnarratives.eu/resource/Q1286> 
    <https://dlnarratives.eu/resource/Q63817>  
    <https://dlnarratives.eu/resource/Q190348>
    <https://dlnarratives.eu/resource/Q64848883>   
    <https://dlnarratives.eu/resource/Q25220> 
    <https://dlnarratives.eu/resource/Q327434>   
    <https://dlnarratives.eu/resource/Q1251>
     
    }

}

orderby ?CountryName
```

#### Results

```csv
"title","narrative","CountryName"
"Holzwelt Murau (transl.: World of wood Murau)","https://dlnarratives.eu/narrative/N114_VC_07_AT","Austria"
"Murauer Beer","https://dlnarratives.eu/narrative/N132_VC_08_AT","Austria"
"Murauer Heumilch (transl.: hay milk)","https://dlnarratives.eu/narrative/N151_VC_09_AT","Austria"
"Osttiroler Mountain Lamb ('Osttiroler Berglamm')","https://dlnarratives.eu/narrative/N168_VC_10_AT","Austria"
"Murbodner Potatoes ('Murbodner Erdäpfel')","https://dlnarratives.eu/narrative/N185_VC_11_AT","Austria"
"ALMO alp oxen","https://dlnarratives.eu/narrative/N1_VC_01_AT","Austria"
"Lungauer Eachtling","https://dlnarratives.eu/narrative/N204_VC_12_AT","Austria"
"Wines of Kitzek im Sausal, South Styria","https://dlnarratives.eu/narrative/N21_VC_21_AT","Austria"
"Mölltal-Glockner Lamm (engl. lamb)","https://dlnarratives.eu/narrative/N223_VC_13_AT","Austria"
"Murbodner Beef","https://dlnarratives.eu/narrative/N239_VC_14_AT","Austria"
"Almenland Stollenkäse (transl.: Almenland mining tunnel cheese)","https://dlnarratives.eu/narrative/N23_VC_02_AT","Austria"
"Sheep farmers from Weiz ('Weizer Schafbauern')","https://dlnarratives.eu/narrative/N257_VC_15_AT","Austria"
"Kärntna Låxn","https://dlnarratives.eu/narrative/N275_VC_16_AT","Austria"
"Ausseerland Seesaibling und Forelle (engl. artic char and trout from the Region Ausseerland)","https://dlnarratives.eu/narrative/N291_VC_17_AT","Austria"
"Murtaler pumpkin seed oil ('Kürbiskernöl')","https://dlnarratives.eu/narrative/N308_VC_18_AT","Austria"
"Bio vom Berg (translated: Organic products from mountain areas)","https://dlnarratives.eu/narrative/N326_VC_19_AT","Austria"
"Almenland Herbs","https://dlnarratives.eu/narrative/N41_VC_03_AT","Austria"
"Almenland Fish","https://dlnarratives.eu/narrative/N59_VC_04_AT","Austria"
"Styria Beef","https://dlnarratives.eu/narrative/N5_VC_20_AT","Austria"
"Almenland Honey","https://dlnarratives.eu/narrative/N77_VC_05_AT","Austria"
"Almenland Schnaps","https://dlnarratives.eu/narrative/N95_VC_06_AT","Austria"
"Stone fruits: peach and abricot","https://dlnarratives.eu/narrative/N102_VC_06_FR1","France"
"Picodon AOP","https://dlnarratives.eu/narrative/N120_VC_07_FR1","France"
"Fruits with pips","https://dlnarratives.eu/narrative/N139_VC_08_FR1","France"
"Agriturism - turistic and pedagogic welcome in farms","https://dlnarratives.eu/narrative/N158_VC_09_FR1","France"
"Organic still wine production","https://dlnarratives.eu/narrative/N174_VC_10_FR1","France"
"Garlic production","https://dlnarratives.eu/narrative/N192_VC_11_FR1","France"
"Corsican Milk Kid","https://dlnarratives.eu/narrative/N210_VC_12_FR","France"
"Poultry farming, integration by the cooperative","https://dlnarratives.eu/narrative/N211_VC_12_FR1","France"
"Extensive and pastoral livestock farming","https://dlnarratives.eu/narrative/N29_VC_02_FR1","France"
"Market gardening, diversified with organic value chain and local channels distribution","https://dlnarratives.eu/narrative/N47_VC_03_FR1","France"
"Clairette de Die - Wine production targeted towards export","https://dlnarratives.eu/narrative/N65_VC_04_FR1","France"
"Organic, aromatic and medicinal plants","https://dlnarratives.eu/narrative/N84_VC_05_FR1","France"
"Diversification strategies of conventional agriculture in large irrigated field crops","https://dlnarratives.eu/narrative/N9_VC_01_FR1","France"
"Dairy industry","https://dlnarratives.eu/narrative/N103_VC_46_IT","Italy"
"organic eggs","https://dlnarratives.eu/narrative/N105_VC_47_IT","Italy"
"With a open-air grazing in a regional natural park, it is produced and processed high-quality milk","https://dlnarratives.eu/narrative/N107_VC_48_IT","Italy"
"The production is about particular cow cheeses and butter","https://dlnarratives.eu/narrative/N112_VC_50_IT","Italy"
"Old varieties of potatos (Walser Kartoffeln)","https://dlnarratives.eu/narrative/N34_VC_22_IT","Italy"
"Alto Trentino, organic wine production for premium wines","https://dlnarratives.eu/narrative/N40_VC_23_IT","Italy"
"The farm sustainably produces apple and derived alcoholic beverages (cider)","https://dlnarratives.eu/narrative/N57_VC_27_IT","Italy"
"Grapevine on terraces- Valtellina","https://dlnarratives.eu/narrative/N77_VC_34_IT","Italy"
"apples and derivatives and other agricoltural products","https://dlnarratives.eu/narrative/N87_VC_39_IT","Italy"
"PEFC certified wood from collective forests management","https://dlnarratives.eu/narrative/N89_VC_40_IT","Italy"
"La Cattedra- Commons farming for social and educational purposes- Asiago Plateau","https://dlnarratives.eu/narrative/N93_VC_42_IT","Italy"
"A cooperative dairy of local farmers enhancing local traditional cheese production","https://dlnarratives.eu/narrative/N96_VC_43_IT","Italy"
"Processed pork meat","https://dlnarratives.eu/narrative/N98_VC_44_IT","Italy"
"Transhumance livestock","https://dlnarratives.eu/narrative/N74_VC_33_ES","Spain"
"Goat's milk cheese made by hand directly on the mountain pasture","https://dlnarratives.eu/narrative/N113_VC_51_CH2","Switzerland"
"Game animals","https://dlnarratives.eu/narrative/N116_VC_07_CH1","Switzerland"
"Merlot from Alps - Ticino","https://dlnarratives.eu/narrative/N118_VC_CH_01","Switzerland"
"Gruyère is a typical Swiss cheese, produced in the mountain pastures in summer","https://dlnarratives.eu/narrative/N154_VC_09_CH2","Switzerland"
"Beer","https://dlnarratives.eu/narrative/N170_VC_10_CH1","Switzerland"
"'Soglio' cosmetics","https://dlnarratives.eu/narrative/N259_VC_15_CH1","Switzerland"
"Traditional alpine meat (and other animal) products","https://dlnarratives.eu/narrative/N25_VC_02_CH1","Switzerland"
"Mountain honey","https://dlnarratives.eu/narrative/N293_VC_17_CH1","Switzerland"
"Touristic services","https://dlnarratives.eu/narrative/N310_VC_18_CH1","Switzerland"
"Alpine Fish","https://dlnarratives.eu/narrative/N31_VC_22_CH1","Switzerland"
"Traditional alpine dairy products","https://dlnarratives.eu/narrative/N3_VC_01_CH1","Switzerland"
"Traditional alpine fruit products","https://dlnarratives.eu/narrative/N43_VC_03_CH1","Switzerland"
"Wine","https://dlnarratives.eu/narrative/N61_VC_04_CH1","Switzerland"
"'Allesmassiv' carpentry","https://dlnarratives.eu/narrative/N64_VC_29_CH1","Switzerland"
"Alps Art Academy and Art Safiental","https://dlnarratives.eu/narrative/N67_VC_30_CH1","Switzerland"
"Traditional alpine vegetable products","https://dlnarratives.eu/narrative/N97_VC_06_CH1","Switzerland"
```
