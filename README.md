# What does it do
Eine KML-Datei erstellen mit den Bewegungen der Iceberge in der Antarktis.

## Welche Schritte sind dafür notwendig
a) Herunterladen der entsprechenden CSV-Dateien von der Seite https://usicecenter.gov/Products/AntarcIcebergs

b) Speichern der Werte in dieser CSV-Dateien in die Tabellen:
- tx_icebergmap_domain_model_iceberg
- tx_icebergmap_domain_model_icebergdata

c) Erstellen einer KML-Datei aus diesen Daten.

d) Anzeigen der KML-Datei im Frontend

## Programmierung der einzelnen Teile

### a) GetCsvFileCommand.php
Es die aktuelle CSV-Datei runtergeladen und dann wird über einen Eintrag in der
Sys_registry überprüft ob es sich um eine neue Datei handelt, wenn ja wird in der
Sys_registry das Flag 'hasNewFile' auf true gesetzt und der Inhalt in 'lastFileContent'
gespeichert.

Es gibt ein möglichkeit alle CSV-Dateien anzuzeigen unter der Funktion:
https://usicecenter.gov/Products/ArchiveSearchMulti?table=IcebergProducts&linkChange=ant-three

### b) ReadIcebergCsvCommand.php
Dies wird erledigt über das Commando
```
EXT:icebergmap/Classes/Command/ReadIcebergCsvCommand.php
```
Es gibt zwei Quellen aus denen die CSV-Datei gelesen werden können:
* Die Daten die über ``GetCsvFileCommand`` in die Sys_registry gespeichert werden.
* Die Csv-Files auslesen die sich in dem Verzeichnis befinden, die über die Option 
``-p`` dem Comando ``icebergmap:read-iceberg-csv`` mitgegeben wird.

Die Daten werden in den Tabellen 
- tx_icebergmap_domain_model_iceberg
- tx_icebergmap_domain_model_icebergdata

gespeichert. Beim speichern der Daten in  ``tx_icebergmap_domain_model_icebergdata``
wird überprüft ob bereits Daten zu diesem Datum gespeichert wurden und wenn das der 
Fall ist werden die Daten nicht gespeichert. D.h. der Befehl kann mehrmals ausgeführt
werden, aber es besteht keine Gefahr das doppelte Daten entstehen.


**@TODO:** 
- Programmierung hinzufügen, das die path-name-Option benutzt wird um ein ganzes Verzeichnis
einzufügen
- Widget programmieren für die Anzeige der Anzahl der CsvFiles und für das Datum des letzten Files.
- DONE: Den Eintrag wieviele CSV-Datateien eingeleseen wurde in die SysRegisty programmieren
- Eintrag in die sys_registry wie der Name des zuletzt erstellen kml-File ist 


## Sys_registy Einträge

entry_namespace: icebergMap

**entry_key: lastFilename**\
Der Name der letzten Herunter geladenen CSV-Datei

**entry_key: hasNewFile**\
Wenn es eine neue Datei gibt wird dieser Key auf true gestellt, das das nächste 
Kommando weiß das es einen neue Datei gibt.

**entry_key: lastFileContent**\
Der Inhalt der herunter geladenen Datei.

**entry_key: numberOfCsvFiles**\
Anzahl der CSV-Files die ausgewertet wurden

**entry_key: dateOfLastCsvFile**\
Datum des letzten CSV-Files was runtergeladen wurde.



