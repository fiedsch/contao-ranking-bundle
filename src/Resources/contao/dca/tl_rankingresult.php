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

/*
 * This file is part of fiedsch/contao-ranking-bundle.
 *
 * (c) 2016-2021 Andreas Fieger
 *
 * @package Ranking-Turniere
 * @link https://github.com/fiedsch/contao-ranking-bundle/
 * @license https://opensource.org/licenses/MIT
 */

use Contao\Date;
use Contao\DataContainer;
use Contao\Input;
use Fiedsch\RankingBundle\Model\RankingeventModel;
use Fiedsch\RankingBundle\Model\RankingModel;
use Fiedsch\RankingBundle\Model\RankingplayerModel;

$is_overview = 'ranking.result' === Input::get('do');

$GLOBALS['TL_DCA']['tl_rankingresult'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'ptable' => 'tl_rankingevent',
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid,name' => 'unique',
            ],
        ],
        // wenn wir nicht im Mode "display child records" aufgerufen wurden ist
        // der "add"-Button sinnlos, da nicht klar ist, zu welcher 'pid' hinzugefügt
        // werden soll.
        'closed' => 'ranking.result' === Input::get('do'),
    ], // config

    'list' => [
        'sorting' => [
            // Für den Aufruf "als child records" (4) vs "als eigenständige Tabelle (eigener Menüpunkt)" (1)
            'mode' => 'ranking.ranking' === Input::get('do') ? 4 : 1, // 4 Displays the child records of a parent record
            'fields' => ['pid', 'platz'],
            'format' => '%s.',
            'flag' => 11, // 11 == Sort ascending
            'disableGrouping' => 'ranking.ranking' === Input::get('do'),
            'panelLayout' => $is_overview ? 'filter;search,limit' : 'limit',
            'headerFields' => ['date'],
            'child_record_callback' => static function ($row) {
                // Für den Aufruf "als child records"
                $member = RankingplayerModel::findById($row['name']);

                return sprintf('%d. %s', $row['platz'], $member->name);
            },
        ],
        'label' => [
            'fields' => ['platz', 'name:tl_rankingplayer.name'],
            'format' => '%s. %s',
            // Für den Aufruf als "eigenständige Tabelle"
            'label_callback' => static function ($row) {
                $player = RankingplayerModel::findById($row['name']);

                return sprintf('%d. %s', $row['platz'], $player->name);
            },
            'group_callback' => static function ($group, $mode, $field, $row) {
                $event = RankingeventModel::findById($row['pid']);
                $ranking = RankingModel::findById($event->pid);

                return sprintf('%s, %s', $ranking->name, Date::parse('d.m.Y', $event->date));
            },
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingresult']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],

            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingresult']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],

            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingresult']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],

            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingresult']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ], // operations
    ], // list

    'palettes' => [
        '__selector__' => [],
        'default' => '{title_legend},name,platz',
    ], // palettes

    'fields' => [
        'id' => [
            'sql' => 'int(10) unsigned NOT NULL auto_increment',
        ],

        'pid' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],

        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],

        'name' => [
            'label' => &$GLOBALS['TL_LANG']['tl_rankingresult']['name'],
            'exclude' => true,
            'search' => false,
            'filter' => true,
            'inputType' => 'select',
            'eval' => ['doNotCopy' => true, 'tl_class' => 'w50', 'mandatory' => true, 'includeBlankOption' => true, 'chosen' => true],
            'xlabel' => [['\Fiedsch\RankingBundle\Helper\DCAHelper', 'editPlayerWizard']],
            'foreignKey' => 'tl_rankingplayer.name',
            'relation' => ['type' => 'hasOne', 'table' => 'tl_rankingplayer', 'load' => 'lazy'],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],

        'platz' => [
            'label' => &$GLOBALS['TL_LANG']['tl_rankingresult']['platz'],
            'exclude' => true,
            'search' => false,
            'filter' => true,
            'flag' => 11, // sort ascending
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'tl_class' => 'w50', 'rgxp' => 'natural', /*'rgxp' => 'custom', 'customRgxp' => '/^[1-9]\d*$/',*/ 'maxlength' => 4],
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
    ], // fields
];

// wenn wir nicht im Mode "display child records" aufgerufen wurden ist
// der "add"-Button sinnlos, da nicht klar ist, zu welcher 'pid' hinzugefügt
// werden soll. Daher oben 'closed' => false. Der 'copy'-Button ist aus dem
// gleichen Grund unsinnnig:
if ('ranking.result' === Input::get('do')) {
    unset($GLOBALS['TL_DCA']['tl_rankingresult']['list']['operations']['copy']);
}
