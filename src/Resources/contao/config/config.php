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

$ligaverwaltung_index = array_search('liga', array_keys($GLOBALS['BE_MOD']), true);

array_insert($GLOBALS['BE_MOD'], $ligaverwaltung_index ? $ligaverwaltung_index + 1 : 1,
        [
            'ranking' => [
                'ranking.spieler' => [
                    'tables' => ['tl_rankingplayer'],
                ],
                'ranking.ranking' => [
                    'tables' => ['tl_ranking', 'tl_rankingevent', 'tl_rankingresult'],
                ],
                'ranking.result' => [
                    'tables' => ['tl_rankingresult'],
                ],
            ],
        ]
);

// Content Elements

$GLOBALS['TL_CTE']['texts']['rankingranking'] = 'Fiedsch\RankingBundle\ContentRankingRanking';

// Backend-CSS

if (TL_MODE === 'BE') {
    $GLOBALS['TL_CSS'][] = 'bundles/fiedschranking/backend.css';
}
