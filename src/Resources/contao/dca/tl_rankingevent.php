<?php

$GLOBALS['TL_DCA']['tl_rankingevent'] = [
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'ptable' => 'tl_ranking',
        'ctable' => ['tl_rankingresult'],
        'sql' => [
            'keys' => [
                'id'  => 'primary',
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
            'child_record_callback' => function($row) {
                return sprintf("<span class='%s'>%s</span> %s",
                    $row['published'] === '1' ? '' : 'tl_gray',
                    \Date::parse('d.m.Y', $row['date']),
                    $row['published'] === '1' ? '' : '(nicht veröffentlicht)'
                );
            }
        ],
        'label' => [
            'fields' => ['date'],
            'format' => '%s',
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ]
        ],
        'operations' => [

            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingevent']['edit'],
                'href'  => 'table=tl_rankingresult',
                'icon'  => 'edit.svg',
            ],

            'editheader' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingevent']['editheader'],
                'href'  => 'act=edit',
                'icon'  => 'header.svg',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingevent']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.svg',
            ],

            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_rankingevent']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],

            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingevent']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.svg',
            ],
        ], // operations
    ], // list

    'palettes' => [
        '__selector__' => [],
        'default'      => '{title_legend},date,published',
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
            'label'     => &$GLOBALS['TL_LANG']['tl_rankingevent']['date'],
            'exclude'   => true,
            'search'    => false,
            'filter'    => true,
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50 widget', 'mandatory' => true, 'rgxp'=>'date', 'datepicker'=>true, 'maxlength' => 128],
            'flag'      => 7, // Sort by month ascending,
            'sql'       => "varchar(11) NOT NULL default ''",
        ],

        'published' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_rankingevent']['published'],
            'exclude'   => true,
            'search'    => false,
            'filter'    => true,
            'inputType' => 'checkbox',
            'eval'      => ['tl_class' => 'w50 m12'],
            'sql'       => "char(1) NOT NULL default ''",
        ]

    ], // fields

];


// Nur Administratoren dürfen Include-Elemente verwenden
// if (!BackendUser::getInstance()->isAdmin) {
//    unset($GLOBALS['TL_DCA']['tl_rankingevent']['list']['operations']['delete']);
//}
