<?php

declare(strict_types=1);

/*
 * This file is part of fiedsch/contao-ranking-bundle.
 *
 * (c) 2016-2021 Andreas Fieger
 *
 * @package Ranking-Turniere
 * @link https://github.com/fiedsch/contao-ranking-bundle/
 * @license https://opensource.org/licenses/MIT
 */

namespace Fiedsch\RankingBundle\Controller\ContentElement;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\ContentElement;
use Contao\Database;
use Contao\System;
use Fiedsch\RankingBundle\Helper\PunkeHelperInterface;

use function count;

/**
 * Content element "Gesamt-Ranking eines Rankingturniers".
 *
 * @author Andreas Fieger <https://github.com/fiedsch>
 */
class RankingRanking extends ContentElement
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'ce_rankingranking';

    public function generate()
    {
        if (TL_MODE === 'BE') {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->title = $this->headline;

            $objTemplate->wildcard = '### '.$GLOBALS['TL_LANG']['CTE']['rankingranking'][0].' ###';

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the content element.
     */
    public function compile(): void
    {
        $tempdata = [];
        $result = [];

        // Rohdaten holen
        $sql = 'SELECT'
            .' re.id,rr.platz,re.date as re_date,r.name as r_name,rp.name as rp_name,rp.gender as rp_gender'
            .' FROM tl_rankingresult rr'
            .' LEFT JOIN tl_rankingevent re ON (re.id=rr.pid)'
            .' LEFT JOIN tl_ranking r ON (r.id=re.pid)'
            .' LEFT JOIN tl_rankingplayer rp ON (rr.name=rp.id)'
            ." WHERE re.published='1'"
        ;
        $data = Database::getInstance()->prepare($sql)->execute(); // TODO use Doctrine\DBAL\Connection

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
        foreach ($tempdata as $data) {
            foreach ($data as $playerdata) {
                $result[$playerdata['rp_name']]['punkte'] += $playerdata['punkte'];
                ++$result[$playerdata['rp_name']]['teilnahmen'];
                $result[$playerdata['rp_name']]['plaetze'][] = $playerdata['platz'];
                $result[$playerdata['rp_name']]['rp_gender'] = $playerdata['rp_gender'];
            }
        }

        // Benutzerdefiniertes Sortieren:
        //   "Die Vergleichsfunktion muss einen Integer
        //     kleiner als, gleich oder größer als Null
        //   zurückgeben, wenn das erste Argument
        //     respektive kleiner, gleich oder größer
        //   als das zweite ist."
        // (für aufsteigende Sortieruung!).

        uasort($result, static function ($a, $b) { return -1 * ($a['punkte'] - $b['punkte']); });

        // Berechnung Ranglplatz (Ties berücksichtigen!)

        // Filtern nach tl_rankingplayer.gender == 'male' oder 'female' für
        // Damen- bzw. Herren-Ranking
        $result_male = array_filter(
            $result,
            static function ($element) {
                return 'male' === $element['rp_gender'];
            }
        );
        $result_female = array_filter(
            $result,
            static function ($element) {
                return 'female' === $element['rp_gender'];
            }
        );

        // Ränge berechnen und Ergebnisse an das Template weiterreichen

        $this->Template->result = self::computeRanks($result);
        $this->Template->result_female = self::computeRanks($result_female);
        $this->Template->result_male = self::computeRanks($result_male);

        $this->Template->pott = array_reduce($this->Template->result, static function ($i, $el) { return $i + $el['teilnahmen']; }, 0) * Config::get('ranking_pott_betrag');
        $this->Template->pott_female = array_reduce($this->Template->result_female, static function ($i, $el) { return $i + $el['teilnahmen']; }, 0) * Config::get('ranking_pott_betrag');
        $this->Template->pott_male = array_reduce($this->Template->result_male, static function ($i, $el) { return $i + $el['teilnahmen']; }, 0) * Config::get('ranking_pott_betrag');
    }

    /**
     * @return array
     */
    protected static function computeRanks(array $result)
    {
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
                ++$skipraenge;
            }
            $lastpunkte = $result[$player]['punkte'];
        }

        foreach (array_keys($result) as $player) {
            $result[$player]['plaetze_aggr'] = self::reduceArray($result[$player]['plaetze']);
        }

        return $result;
    }

    /**
     * Berechnung der Punkte für erreichten $platz bei $teilnehmerzahl Teilnehmern.
     *
     * @param int $platz
     * @param int $teilnehmerzahl
     *
     * @return int
     */
    protected function getPunkte($platz, $teilnehmerzahl)
    {
        /** @var PunkeHelperInterface $punkteHelper */
        $punkteHelper = System::getContainer()->get('fiedsch_ranking.punktehelper');
        return $punkteHelper->getPunkte($platz, $teilnehmerzahl);
    }

    /**
     * Größe des Teilnehmerfelds (Zweierpotenz).
     *
     * @param int $data
     *
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
     *
     * @return string
     */
    protected static function reduceArray($data)
    {
        $aggr = [];

        foreach ($data as $value) {
            ++$aggr[$value];
        }
        ksort($aggr);
        $result = [];

        foreach ($aggr as $k => $v) {
            if ($v > 1) {
                $result[] = sprintf('<small>%d&times;</small>%d.', $v, $k);
            } else {
                $result[] = sprintf('%s.', $k);
            }
        }

        return implode(', ', $result);
    }
}
