# WLAN Code-Verwaltung

![PHP 8.3](https://img.shields.io/badge/PHP-8.3-success.svg?style=flat-square)
![Symfony 7](https://img.shields.io/badge/Symfony-7-success.svg?style=flat-square)
![MIT](https://img.shields.io/github/license/schulit/wlan-codes.svg?style=flat-square)

Die WLAN Code-Verwaltung kann genutzt werden, um von der pfSense generierte Vouchers für das Captive Portal an
Lehrkräfte weiterzugeben.

## Funktionen

* Admin: Codes importieren (mithilfe von der pfSense generierten CSV-Datei)
    * Codes können abhängig von ihrer Gültigkeitsdauer (in Minuten) importiert werden
* Lehrkräfte: Codes anfordern
    * Auswahl der Dauer möglich
    * Freitext-Kommentar möglich (wird nicht vom System benötigt, hilft aber bei der eigenen Übersicht)

## Handbuch

Das Handbuch ist [hier](https://docs.schulit.de/wlan-codes) zu finden.

## Mitmachen

Mitmachen ist ausdrücklich erwünscht - Bugmeldungen, Funktionswünsche und Pullrequests sind immer herzlich willkommen.
Ein GitHub Account ist erforderlich.

## Lizenz

[MIT](LICENSE)
