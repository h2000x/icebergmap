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

@TODO: 
- Programmierung anpassen, weil das CSV-File jetzt ja in Sys-Registry gespeichert ist
- Programmierung hinzufügen, die das einlesen von CSV-Datei aus einem Verzeichnis ermöglicht.

## Sys_registy Einträge

entry_namespace: icebergMap

**entry_key: lastFilename**\
Der Name der letzten Herunter geladenen CSV-Datei

**entry_key: hasNewFile**\
Wenn es eine neue Datei gibt wird dieser Key auf true gestellt, das das nächste 
Kommando weiß das es einen neue Datei gibt.

**entry_key: lastFileContent**\
Der Inhalt der herunter geladenen Datei.
