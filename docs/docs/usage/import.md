---
sidebar_position: 2
---

# Codes importieren

Die Codes können von der Konfigurationsoberfläche der pfSense als CSV-Datei heruntergeladen werden. 
Anschließend können diese von einem Benutzer mit der Rolle `ROLE_ADMIN` unter *Verwaltung ➜ Codes* 
importiert werden. Dazu muss lediglich die Gültigkeitsdauer der Codes und die CSV-Datei im
Formular auf der rechten Seite eingetragen werden.

Der Import findet in Batches von 1000 Codes statt, da es anderenfalls zu Timeouts kommen kann (insb. 
bei sehr vielen zu importierenden Codes).

:::tip Hinweis
Beim Import werden Codes, die bereits im System existieren, ignoriert. Sie werden also nicht doppelt
importiert, gelöscht oder ähnliches. Auch lässt sich durch einen Import ein Code nicht "zurücksetzen", 
sodass er wieder ausgegeben werden kann.
:::