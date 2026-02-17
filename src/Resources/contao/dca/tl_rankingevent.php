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

use Contao\Date;
use Contao\DC_Table;

/*
 * This file is part of fiedsch/contao-ranking-bundle.
 *
 * (c) 2016-2021 Andreas Fieger
 *
 * @package Ranking-Turniere
 * @link https://github.com/fiedsch/contao-ranking-bundle/
 * @license https://opensource.org/licenses/MIT
 */

$GLOBALS['TL_DCA']['tl_rankingevent'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'ptable' => 'tl_ranking',
        'ctable' => ['tl_rankingresult'],
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'pid' => 'index',
            ],
        ],
    ], // config

    'list' => [
        'sorting' => [
            'mode' => 4, // 4 Displays the child records of a parent record
            'fields' => ['date'],
            'flag' => 1, // 1 == Sort by initial letter ascending
            'panelLayout' => 'filter;search,limit',
            'headerFields' => ['name'],
            'child_record_callback' => static function ($row) {
                return sprintf("<span class='%s'>%s</span> %s",
                    '1' === $row['published'] ? '' : 'tl_gray',
                    Date::parse('d.m.Y', $row['date']),
                    '1' === $row['published'] ? '' : '(nicht veröffentlicht)'
                );
            },
        ],
        'label' => [
            'fields' => ['date'],
            'format' => '%s',
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
            'edit',
            'children',
            'copy',
            'delete',
            'show',
            'toggle'
        ], // operations
    ], // list

    'palettes' => [
        '__selector__' => [],
        'default' => '{title_legend},date,published',
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

        'date' => [
            'label' => &$GLOBALS['TL_LANG']['tl_rankingevent']['date'],
            'exclude' => true,
            'search' => false,
            'filter' => true,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50 widget', 'mandatory' => true, 'rgxp' => 'date', 'datepicker' => true, 'maxlength' => 128],
            'flag' => 7, // Sort by month ascending,
            'sql' => "varchar(11) NOT NULL default ''",
        ],

        'published' => [
            'label' => &$GLOBALS['TL_LANG']['tl_rankingevent']['published'],
            'toggle' => true,
            'exclude' => true,
            'search' => false,
            'filter' => true,
            'inputType' => 'checkbox',
            'eval' => ['tl_class' => 'w50 m12'],
            'sql' => "char(1) NOT NULL default ''",
        ],
    ], // fields
];

// Nur Administratoren dürfen Include-Elemente verwenden
// if (!BackendUser::getInstance()->isAdmin) {
//    unset($GLOBALS['TL_DCA']['tl_rankingevent']['list']['operations']['delete']);
//}
