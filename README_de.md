# Polyglot_XH

Polyglot_XH bietet fortgeschrittene Features für mehrsprachige CMSimple_XH
Websites. Hauptsache ist die Möglichkeit einzelne Seiten verschiedener Sprachen
als tatsächliche Übersetzungen zu markieren, so dass Besucher wie auch der
Administrator schnell zwischen den Übersetzungen einer bestimmten Seite
umschalten können.

Weiterhin gibt das Plugin automatisch Informationen hauptsächlich für
Suchmaschinen bezüglich verfügbarer Übersetzungen der aktuellen Seite aus (so
genannte
[hreflang Links](https://support.google.com/webmasters/answer/189077?hl=de)).

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
- [Problembehebung](#problembehebung)
- [Lizenz](#lizenz)
- [Danksagung](#danksagung)

## Voraussetzungen

Polyglot_XH ist ein Plugin für CMSimple_XH.
Es benötigt CMSimple_XH ≥ 1.7.0 und PHP ≥ 5.4.0.

## Download

Das [aktuelle Release](https://github.com/cmb69/polyglot_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple_XH-Plugins auch.

1. Sichern Sie die Daten auf Ihrem Server.
1. Entpacken Sie die ZIP-Datei auf Ihrem Computer.
1. Laden Sie das gesamte Verzeichnis `polyglot/` auf Ihren Server in das
   `plugins/` Verzeichnis von CMSimple_XH hoch.
1. Vergeben Sie Schreibrechte für die Unterverzeichnisse `cache/`, `css/`,
   `config/` und `languages/`.
1. Navigieren Sie zu `Plugins` → `Polyglot` im Administrationsbereich,
   und prüfen Sie, ob alle Voraussetzungen für den Betrieb erfüllt sind.

## Einstellungen

Die Konfiguration des Plugins erfolgt wie bei vielen anderen
CMSimple_XH-Plugins auch im Administrationsbereich der Website.
Navigieren Sie zu `Plugins` → `Polyglot`.

Sie können die Original-Einstellungen von Polyglot_XH unter `Konfiguration`
ändern. Beim Überfahren der Hilfe-Icons mit der Maus werden Hinweise zu den
Einstellungen angezeigt.

Die Lokalisierung wird unter `Sprache` vorgenommen. Sie können die
Zeichenketten in Ihre eigene Sprache übersetzen (falls keine entsprechende
Sprachdatei zur Verfügung steht), oder sie entsprechend Ihren Anforderungen
anpassen.

Das Aussehen von Polyglot_XH kann unter `Stylesheet` angepasst werden.

## Verwendung

Um das fortgeschrittene Sprachmenü von Polyglot_XH zu nutzen, ersetzen Sie
den Aufruf von `languagemenu()` in Ihrem/Ihren Template(s) mit:

    <?php echo polyglot_languagemenu();?>

Damit das wirklich funktioniert, müssen Sie individuelle Seiten
unterschiedlicher Sprachen geeignet markieren. Sie können dies im Reiter
`Polyglot` oberhalb des Content-Editors tun; vergeben Sie einfach das selbe
eindeutige Schlagwort für die Originalseite und für ihre Übersetzungen.

In der Plugin-Administration (`Plugins` → `Polyglot` → `Übersetzungen`)
erhalten Sie einen Überblick darüber, welche Seiten bereits übersetzt wurden.
Die Seitenüberschriften sowie die Übersetzungen sind zu den entsprechenden
Seiten im Bearbeitungsmodus verlinkt, so dass Sie schnell möglicherweise
fehlende Polyglot_XH Schlagwörter nachbessern können.

Beachten Sie, dass die Beziehungen zwischen Seiten verschiedener Sprachen
unter `plugins/polyglot/cache/` aus Performance-Gründen zwischengespeichert
werden. Falls Sie diesen Cache, aus welchen Gründen auch immer, löschen müssen,
dann müssen Sie wenigstens eine Seite **jeder** Sprache speichern, um ihn
korrekt wieder aufzubauen.

## Problembehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf
[Github](https://github.com/cmb69/polyglot_xh/issues)
oder im [CMSimple\_XH Forum](https://cmsimpleforum.com/).

## Lizenz

Polyglot_XH ist freie Software. Sie können es unter den Bedingungen
der GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Polyglot_XH erfolgt in der Hoffnung, daß es
Ihnen von Nutzen sein wird, aber *ohne irgendeine Garantie*, sogar ohne
die implizite Garantie der *Marktreife* oder der *Verwendbarkeit für einen
bestimmten Zweck*. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Polyglot_XH erhalten haben. Falls nicht, siehe <https://www.gnu.org/licenses/>.

Copyright 2012-2021 Christoph M. Becker

## Danksagung

Polyglot_XH wurde durch
[Multilang_XH](https://cmsimplewiki-com.keil-portal.de/doku.php?id=plugins:multilang_xh)
von [Jesper Zedlitz](https://www.zedlitz.de/) angeregt.

Das Plugin-Logo wurde von Lakshman Poonyth gestaltet.
Vielen Dank für die Veröffentlichung auf
[openclipart.org](https://openclipart.org/detail/13039/globe-of-flags-by-anonymous-13039).

Vielen Dank an die Gemeinschaft im
[CMSimple_XH-Forum](https://www.cmsimpleforum.com/)</a> für Tipps, Anregungen und das Testen.

Zu guter Letzt vielen Dank an [Peter Harteg](https://harteg.dk/), den „Vater“ von CMSimple,
und allen Entwicklern von [CMSimple_XH](https://www.cmsimple-xh.org/de/),
ohne die dieses fantastische CMS nicht existieren würde.
