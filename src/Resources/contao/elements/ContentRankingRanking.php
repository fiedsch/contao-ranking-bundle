<?php


/**
 * Content element "Gesamt-Ranking eines Rankingturniers".
 *
 * @author Andreas Fieger <https://github.com/fiedsch>
 */
class ContentRankingRanking extends \ContentElement
{
    /**
     * Template
     *
     * @var string
     */
    protected $strTemplate = 'ce_rankingranking';

    public function generate()
    {
        if (TL_MODE == 'BE') {
            $objTemplate = new \BackendTemplate('be_wildcard');
            $objTemplate->title = $this->headline;


            $objTemplate->wildcard = "### " . $GLOBALS['TL_LANG']['CTE']['rankingranking'][0] . " ###";
            return $objTemplate->parse();
        }
        return parent::generate();
    }

    /**
     * Generate the content element
     */
    public function compile()
    {
        $tempdata = [];
        $result = [];

        // Rohdaten holen
        $sql = "SELECT"
            . " re.id,rr.platz,re.date as re_date,r.name as r_name,rp.name as rp_name"
            . " FROM tl_rankingresult rr"
            . " LEFT JOIN tl_rankingevent re ON (re.id=rr.pid)"
            . " LEFT JOIN tl_ranking r ON (r.id=re.pid)"
            . " LEFT JOIN tl_rankingplayer rp ON (rr.name=rp.id)"
            . " WHERE re.published='1'"
        ;
        $data = \Database::getInstance()->prepare($sql)->execute();
        if ($data) {
            while ($data->next()) {
                $tempdata[$data->id][] = $data->row();
            }
        }

        // Daten anreichern
        foreach ($tempdata as $event => $data) {
            // Anzahl Teilnehmer
            foreach ($data as $i => $playerdata) {
                $tempdata[$event][$i]['teilnehmerzahl'] = count($data);
            }
            // Punkte
            foreach ($data as $i => $playerdata) {
                $tempdata[$event][$i]['punkte'] = $this->getPunkte($tempdata[$event][$i]['platz'], $tempdata[$event][$i]['teilnehmerzahl']);
            }
        }

        // Aggregieren (nach Spieler)
        foreach ($tempdata as $event => $data) {
            foreach ($data as $playerdata) {
                $result[$playerdata['rp_name']]['punkte'] += $playerdata['punkte'];
                $result[$playerdata['rp_name']]['teilnahmen']++;
                $result[$playerdata['rp_name']]['plaetze'][] = $playerdata['platz'];
            }
        }

        // Benutzerdefiniertes Sortieren:
        //   "Die Vergleichsfunktion muss einen Integer
        //     kleiner als, gleich oder größer als Null
        //   zurückgeben, wenn das erste Argument
        //     respektive kleiner, gleich oder größer
        //   als das zweite ist."
        // (für aufsteigende Sortieruung!).

        uasort($result, function($a, $b) { return -1*($a['punkte'] - $b['punkte']); });

        // Berechnung Ranglplatz (Ties berücksichtigen!)

        $rang = 0;
        $skipraenge = 0;
        $lastpunkte = PHP_INT_MAX;
        foreach ($result as $player => $playerdata) {
            if ($playerdata['punkte'] < $lastpunkte) {
                $rang += $skipraenge;
                $result[$player]['rang'] = ++$rang;
                $skipraenge = 0;
            } else {
                $result[$player]['rang'] = $rang;
                $skipraenge++;
            }
            $lastpunkte = $result[$player]['punkte'];
        }

        foreach ($result as $player => $playerdata) {
            $result[$player]['plaetze_aggr'] = self::reduceArray($result[$player]['plaetze']);
        }

        // Ergebnisse an das Template weiterreichen

        $this->Template->result = $result;
    }

    /**
     * Berechnung der Punkte für erreichten $platz bei $teilnehmerzahl Teilnehmern.
     *
     * @param integer $platz
     * @param integer $teilnehmerzahl
     * @return integer
     */
    protected function getPunkte($platz, $teilnehmerzahl)
    {
        // https://www.munich-darts-challenge.de/?pageIdx=7
        return max(232 - (200 * ($platz/$teilnehmerzahl)), 40);

        // simple Dummyimplementierung (nur Debug)
        // return log(self::getFieldSize($teilnehmerzahl), 2.0)*16 + 1 - $platz;

        // 1. Größe des "Felds" aus Anzahl der Teilnehmer, aufgerundet auf die
        // nächst höhere Zweierpotenz (Bsp.: 7 Teilnehmer => 8er Spielplan,
        // 8 Teilnehmer => 8er Spielplan, 9 Teilnehmer => 16er Spielplan, usw.).

        // 2. Abhängig von Feld-Größe und erreichtem $platz die Punkte vergeben

        // siehe z.B. auch
        // https://www.munich-darts-challenge.de/?pageIdx=7
        // http://www.happygame.at/pages/td-challenge/spielregeln.php
    }

    /**
     * Größe des Teilnehmerfelds (Zweierpotenz)
     * @param int $teilnehmer
     * @return float|int
     */
    /*
    protected static function getFieldSize($teilnehmer)
    {
        // 1. Logarithmus zur Basis 2 berechnen
        // 2. wenn keine ganze Zahl, dann aufrunden (ceil())
        // 3. Als Umkehrfunktiopn zu log_2(): 2 mit dem Wert potenzieren
        return pow(2.0, ceil(log($teilnehmer, 2.0)));
    }
    */

    /**
     * Die List der Platzierungen sortieren und "komprimieren" ("3x2." anstelle "2.,2.,2.").
     *
     * @param array $data
     * @return string
     */
    protected static function reduceArray($data)
    {
        $aggr = [];
        foreach ($data as $value) {
            $aggr[$value]++;
        }
        ksort($aggr);
        $result = [];
        foreach ($aggr as $k => $v) {
            if ($v > 1) {
                $result[] = sprintf("%s&times;%s.", $v, $k);
            } else {
                $result[] = sprintf("%s.", $k);
            }
        }
        return implode(", ", $result);
    }
}