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

### a)
Programmierung fehlt noch.

Es gibt ein möglichkeit alle CSV-Dateien anzuzeigen unter der Funktion:
https://usicecenter.gov/Products/ArchiveSearchMulti?table=IcebergProducts&linkChange=ant-three

Aber Ziel ist es das das es einen Cronjob gibt der täglich Überprüft ob auf der Seite https://usicecenter.gov/Products/AntarcIcebergs eine 
neue CSV-Datei gibt.

Todo
* command anlegen
* guzzle ausprobieren 

### b)
Dies wird erledigt über das Commando
```
EXT:icebergmap/Classes/Command/ReadIcebergCsvCommand.php
```

## Sys_registy Einträge

entry_namespace: icebergMap

**entry_key: lastFilename**\
Der Name der letzten Herunter geladenen CSV-Datei

**entry_key: hasNewFile**\
Wenn es eine neue Datei gibt wird dieser Key auf true gestellt, das das nächste 
Kommando weiß das es einen neue Datei gibt.

**entry_key: lastFileContent**\
Der Inhalt der herunter geladenen Datei.
