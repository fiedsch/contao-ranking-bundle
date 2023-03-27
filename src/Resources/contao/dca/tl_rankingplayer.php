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

use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_rankingplayer'] = [
    'config' => [
        'dataContainer' => DC_Table::class,
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
                'name' => 'unique',
            ],
        ],
    ], // config

    'list' => [
        'sorting' => [
            'mode' => 1,
            'fields' => ['name'],
            'flag' => 1, // 1 == Sort by initial letter ascending
            'panelLayout' => 'filter;search,limit',
        ],
        'label' => [
            'fields' => ['name'],
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
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],

            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],

            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],

            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['show'],
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ], // operations
    ], // list

    'palettes' => [
        '__selector__' => [],
        'default' => '{title_legend},name,gender',
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
            'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['name'],
            'exclude' => true,
            'search' => true,
            'filter' => false,
            'inputType' => 'text',
            'eval' => ['tl_class' => 'w50', 'maxlength' => 128, 'mandatory' => true, 'unique' => true],
            'sql' => "varchar(128) default NULL",
        ],

        'gender' => [
            'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['gender'],
            'exclude' => true,
            'search' => false,
            'filter' => true,
            'inputType' => 'select',
            'options' => ['male', 'female'],
            'reference' => &$GLOBALS['TL_LANG']['MSC'],
            'eval' => ['includeBlankOption' => true, 'tl_class' => 'w50', 'mandatory' => true],
            'sql' => "varchar(32) NOT NULL default ''",
        ],
    ], // fields
];
