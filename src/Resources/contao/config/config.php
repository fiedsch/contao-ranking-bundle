<?php

declare(strict_types=1);

use Contao\ArrayUtil;
use Contao\System;
use Fiedsch\RankingBundle\Controller\ContentElement\RankingRanking;
use Fiedsch\RankingBundle\Model\RankingeventModel;
use Fiedsch\RankingBundle\Model\RankingModel;
use Fiedsch\RankingBundle\Model\RankingplayerModel;
use Fiedsch\RankingBundle\Model\RankingresultModel;
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

ArrayUtil::arrayInsert($GLOBALS['BE_MOD'], $ligaverwaltung_index ? $ligaverwaltung_index + 1 : 1,
        [
            'ranking' => [
                'ranking_spieler' => [
                    'tables' => ['tl_rankingplayer'],
                ],
                'ranking_ranking' => [
                    'tables' => ['tl_ranking', 'tl_rankingevent', 'tl_rankingresult'],
                ],
            ],
        ]
);

// Content Elements

$GLOBALS['TL_CTE']['texts']['rankingranking'] = RankingRanking::class;

// Backend-CSS

if (System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(
    System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create('')
)) {
    $GLOBALS['TL_CSS'][] = 'bundles/fiedschranking/backend.css';
}


$GLOBALS['TL_MODELS']['tl_ranking']       = RankingModel::class;
$GLOBALS['TL_MODELS']['tl_rankingevent']  = RankingeventModel::class;
$GLOBALS['TL_MODELS']['tl_rankingplayer'] = RankingplayerModel::class;
$GLOBALS['TL_MODELS']['tl_rankingresult'] = RankingresultModel::class;
