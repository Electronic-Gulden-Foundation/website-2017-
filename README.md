# e-Gulden website 2017-....

## Samenvatting

De e-Gulden website is sinds maart 2014 aktief voor de begeleiding van het e-Gulden/EFL cryptogeld.
Het taalgebruik op de website is Nederlands omdat e-Gulden een voorzet is om te worden opgepakt voor specifiek Nederlandse betalingsverkeer.

Omdat cryptogeld via internet verspreid wordt is een landsgrens kunstmatig. Maar door het bezit, de overdracht, de promotie en de ondersteuning van een cryptomunt te combineren met een specifieke taal wordt de groei van een lokaal geldsysteem gestimuleerd.

Het belang van het ontstaan van lokale cryptogeldnetwerken daarentegen verdient internationale aandacht. Daarom blijven een aantal internationale samenvattingen en vertalingen nodig. En mischien nog wel belangrijker: samenwerking en uitwisseling tussen de verschillende lokale initiatieven.

Een aantal hoofdredenen voor de ontwikkeling van lokale geldsystemen zijn :
- De ontlasting van het totale systeem van cryptogeld
- Verbeterde lokale adoptie van cryptogeld
- Alle positieve aspecten vqn decentralisatie
- De versterking van een lokale economische identiteit

Een tweede reden waarom het ontstaan van een lokaal cryptogeldnetwerk kunstmatig lijkt is de organisatie ervan.
Een team moet het initiatief nemen voor de oprichting, maar de identiteit van dat team botst onmiddellijk met de identiteit van de doelgroep.
Het oprichtingsteam kan haar vrijwilligheid benadrukken en aantonen, maar de doelgroep zal het totale project moeten omarmen.

Een samenwerkingsinstrument als Git is bij uitstek geschikt om een gemeenschap van belanghebbenden te laten samenwerken aan een gemeenschappelijk project.
Zo zal niet alleen de broncode van de e-Gulden software en die van de huidige en toekomstige website, maar uiteindelijk het totale e-Gulden 
cryptogeldproject als voorzet aan de Nederlanse gemeenschap ter beschikking worden gesteld.

# Website Instructies

## Inhoudsopgave

1. Een korte geschiedenis van de website

2. Wat heb je nodig om te sleutelen aan de website

3. Aanbevelingen voor wijzigingen

4. Project navigate

5. Licentie 


## 1. Een korte geschiedenis van de website

De oorspronkelijke oprichters van de e-Gulden blockchain hanteerden een website gebaseerd op het Joomla CMS - Content Management Systeem.
Deze was gehost op het domein e-Gulden.com. De overdracht, drie maanden later naar de Stichting Electronic Gulden Foundation, betrof uitsluitend
een backup van de oorspronkelijke omgeving. Het leek de stichting toepasselijker om de domeinnaam e-gulden.org te reserveren en de hosting daarop te laten plaatsvinden.

De Joomla voorzet is met een stofkam nagelopen en kon tot eind 2016 op hoofdlijnen voldoen om de akties, plannen en opinies van de stichting te presenteren.
Eind 2016 werd het door een Joomla hack dringend noodzakelijk om de website te herbouwen. Besloten werd om Joomla te verlaten en de totale inhoud om te zetten naar een statische website.
Dat wil zeggen een website zonder CMS systeem of database.

Het doel van de omzetting was om Joomla zo snel mogelijk te verlaten en niet om de website onmiddelijk te verbeteren.
Het standpunt was dat de ontwikkeling van een nieuwe website bij uitstek een open source project zou kunnen zijn en gedreven moest worden door enthausiastelingen uit de community.
Gedurende twee jaar zijn diverse voorstellen gedaan om de website te verbeteren, maar structurele projectinitiatieven bleven uit.

In de tussentijd zijn wel een aantal nieuw websiteontwerpen ingediend en bestaat het voornemen om voor juli 2019 een nieuwe website te lanceren. Om een voorzet te geven voor de opensource gedachte is de huidige website daarvoor geschikt gemaakt en bij deze op Gitlab aan de community overgedragen.
De huidige website zal daarom nog een tijd lang blijven bestaan, bijvoorbeeld als archief, maar uiteindelijk als hoofdwebsite worden vervangen.
Parallel daaraan ontstaat op Gitlab de bron voor een nieuwe website. We hopen dat zoveel mogelijk Nederlanders daaraan willen bijdragen en we nodigen ook Nederlandse websiteprofessionals uit om te helpen om dit nieuwe project te managen.

## 2. Wat heb je nodig om te sleutelen aan de website

* GitLab/Github: Alle website broncode is beschikbaar op [GitLab](https://gitlab.com/electronic-gulden-foundation/egulden), 
  * Maak een account aan op gitlab.
  * Initieel heb je rechten als [gast](https://gitlab.com/help/user/permissions), maar daarmee kun je
    * je de broncode downloaden, aanpassen en de aanpassingen testen.
    * opmerkingen en voorstellen ter verbetering plaatsen
  
* PHP: Alleen nodig als je de website op je eigen computer wilt draaien
  * Installeer [PHP](http://php.net/downloads.php)
  Â° Activeer de ingebouwde PHP webserver op een vrije internetpoort:
    php -S 127.0.0.1:1337 -t <rootdirectory>
  * Browse naar http://serveradres:internetpoort

* Ervaring met HTML: alleen nodig als bestaande inhoud wilt aanpassen of nieuwe artikels wilt plaatsen

* Ervaring met CSS en Google Bootstrap: alleen nodig als je aanpassingen aan de websitestructuur wilt aanbrengen

## 3. Aanbevelingen voor wijzigingen

Wat we willen bereiken is dat zoveel mogelijk Nederlanders de weg vinden naar het e-Gulden Gitlab project. 
Het "oude" websiteproject is een laagdrempelige insteek, omdat alleen al het verbeteren van taalfouten of zinsconstructies zinvol is en zo de laagst denkbare drempel is.

  * Suggesties/issues: 
    Iedereen met een account op Gitlab kan suggesties indienen. In de menubalk rechtsboven is dit bereikbaar als "issue"
  * wijzigingsvoorstel/push-request: 
    Het gebruik van Git houdt in dat een deelnemer een volledige kopie van het project in eigen beheer krijgt voor een vooraf te benoemen wijzigingsdoel.
    In je eigen omgeving kun je ongestoord aan dat doel werken en de resultaten testen. Alle wijzigingen zijn precies te zien en kunnen met zogenaamde "commits" worden gegroepeerd en van commentaar voorzien.
  * Het [e-Gulden forum](https://forum.e-gulden.org) is bij uitstek geschikt om hulp bij dit proces te zoeken en te bieden
  
## 4. Project navigatie

* Iedere webpagina wordt opgebouwd met de centrale PHP-routine "index.php"
* index.php laadt een aantal templates uit de template directory
* De eerste parameter achter index.php verwijst naar een bronbestand op de directory "blg"
* De eerste regel van ieder bronbestand bevat een aantal componenten die tevens ingeladen moeten worden en die zich ook op de blg directory bevinden.
* De tweede regel van ieder bronbestand fungeert als pagina-titel. Deze kan worden onderdrukt met een 'h1' element in de verdere code.
* Als het bronbestand op de blg-directory de extensie .txt heeft, wordt dit eenmalig gecompileerd ("parseblog.php") naar de obj-directory
* .txt bestanden kunnen uitsluitend tekst en een beperkte verzameling kale html-elementen bevatten (zie "parseblog.php").

## 5. Licentie

The MIT License (MIT)

Copyright (c) 2009-2019 The Bitcoin Core developers
Copyright (c) 2009-2019 Bitcoin Developers
Copyright (c) 2014-2019 e-Gulden Ontwikkelaars

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.