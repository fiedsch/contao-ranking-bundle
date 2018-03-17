# Ranking Turniere

**Work in Progress, not yet ready to use!**

Eine Turnierserie, die

* an verschiedenen Tagen
* an verschiedenen Orten (Kneipen)
* mit jeweils unterscheidlichen Teilnehmerzahlen

gespielt wird. Eine solches Veranstaltung bezeichnen wir als "Ranking-Event".

Ergebnis soll eine Gesamttabelle sein, die sich aus den Punkten ergibt,
die ein Spieler bei seinen Teilnahmen an Ranking-Events erzielt hat.

## Punktevergabe

Abhängig von der der erreichten Platzierung und der Teilnehmerzahl beim jeweiligen
Ranking-Event werden unterschiedliche Punkte vergeben.

* TODO Punkteschlüssel

## Datenstrukturen

An Orte/Veranstalter/"Rankings" (`tl_ranking`) finden Rankingevents (`tl_rankingevent`)
 statt, an denen Spieler Ergebnissse (`tl_rankingresult`) erzielen.

Damit ergibt sich folgende hierarchische Datenstrutur:

```
tl_ranking: id, name
tl_rankingevent: id, pid=tl_ranking.id, datum
tl_rankingresult: id, pid=tl_rankingevent.id, spieler=tL_rankingplayer.id
tL_rankingplayer: id, name
```

### `tl_member` vs `tl_rankingplayer`

* Die Verwendung von `tl_member` erscheint zunächst naheliegend.
* ABER: Bei Integration auf einer Site, die auch den Contao-Ligamanager verwendet tauchen die
  nur für Rankinga angelegten Mitglieder an vielen Stellen zur Auswahl auf, wo sie keinen
  Sinn ergeben. Zudem werden wesentlich weniger Datenfelder benötigt, als sie in `tl_member`
  bereitgestellt werden.
* Die Verwendung einer eigenen Tabelle (`tl_rankingplayer`), die extra für diesen Zweck
  angelegt wird umgeht diese Probleme. Vorteil zudem: wir könnten als `name` ein einzelnes
  Feld definieren, das Vor- und Nachnamen oder ein Pseudonym enthält.
* Nachteil: bereits existierende Mitglieder (Ligamanager-Spieler) müssen erneut angelegt werden.
  (Hier könnte aber einmalig ein Skript Einräge aus `tl_member` auslesen und in
  `tl_rankingplayer` eintragen).

## Datenerfassung

* neues "Rankingevent" anlegen (entspricht eingegangenem Ergebnisbericht)
* Für jede Zeile einen neuen "Rankingplayer" als Kindelement angelegten
  * Spieler auswählen
  * Platz eintragen (die Punkte werden automatisch berechnet, da sie sich aus einem
    festen Punkteschlüssel ergeben).

## Implementierung

* DCA
  * `tl_ranking`
  * `tl_rankingevent`
  * `tl_rankingresult`
  * `tl_rankingplayer`
* Frontendmodule:
  * Rankingtabelle (insgesamt und optional für ein "Ranking")
  * Frontendmodul "Ergebnisse übermitteln"? (Großes Thema dürfte hier wohl die eindeutige
    Benennung der Spieler sein. Z.B. untescheidliche Schreibweisen des gleichen Namens,
    oder "Andreas" vs. "Fiedsch" vs. ... für die gleiche Person. Wir können aber die Namen
    nicht aus einem Dropdown auswählen lassen, da neue Spieler jederzeit hinzu kommen
    können ohne sich vorher registrieren zu müssen.
    Idee: evtl. alle bereits reg. Spieler als Daten auf der Seite und ein Textfeld mit
    Autocomplete, das aber auch vollkommen neue Namen zulässt. Neue Namen könnten wir
    dann automatisch in der DB als neue `tl_rankingplayer`-Records anlegen. Bei Duplikaten
    müssten dann Datenkorrekturen stattfinden: (1) doppelten "Rankingplayer" löschen und
    (2) die id im "Rankingresult" nei setzen. Das sollte mittels filtern und
    "mehrere bearbeiten" relativ zügig gehen. (Dazu evtl. die `tl_rankingresult` losgelöst
    von der hierarchischen Datenstruktur als eigene Backend-Menüpunkt anbieten?
