<?php

declare(strict_types=1);

use Contao\System;
use Symfony\Component\HttpFoundation\Request;

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

\Contao\ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], $ligaverwaltung_index ? $ligaverwaltung_index + 1 : 1,
        [
            'ranking' => [
                'ranking_spieler' => [
                    'tables' => ['tl_rankingplayer'],
                ],
                'ranking_ranking' => [
                    'tables' => ['tl_ranking', 'tl_rankingevent', 'tl_rankingresult'],
                ],
                'ranking_result' => [
                    'tables' => ['tl_rankingresult'],
                ],
            ],
        ]
);

// Content Elements

$GLOBALS['TL_CTE']['texts']['rankingranking'] = \Fiedsch\RankingBundle\Controller\ContentElement\RankingRanking::class;

// Backend-CSS

if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(
    System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create('')
)) {
    $GLOBALS['TL_CSS'][] = 'bundles/fiedschranking/backend.css';
}


$GLOBALS['TL_MODELS']['tl_ranking']       = \Fiedsch\RankingBundle\Model\RankingModel::class;
$GLOBALS['TL_MODELS']['tl_rankingevent']  = \Fiedsch\RankingBundle\Model\RankingeventModel::class;
$GLOBALS['TL_MODELS']['tl_rankingplayer'] = \Fiedsch\RankingBundle\Model\RankingplayerModel::class;
$GLOBALS['TL_MODELS']['tl_rankingresult'] = \Fiedsch\RankingBundle\Model\RankingresultModel::class;
