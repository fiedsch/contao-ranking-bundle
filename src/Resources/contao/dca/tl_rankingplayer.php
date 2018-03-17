<?php

$GLOBALS['TL_DCA']['tl_rankingplayer'] = [
    'config' => [
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id'          => 'primary',
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
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ]
        ],
        'operations' => [

            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.svg',
            ],

            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.svg',
            ],

            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_rankingplayer']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if (!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\')) return false; Backend.getScrollOffset();"',
            ],

            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_rankingplayer']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.svg',
            ],
        ], // operations
    ], // list

    'palettes' => [
        '__selector__' => [],
        'default'      => '{title_legend},name',
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
            'label'     => &$GLOBALS['TL_LANG']['tl_rankingplayer']['name'],
            'exclude'   => true,
            'search'    => false,
            'filter'    => true,
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50', 'maxlength' => 128],
            'sql'       => "varchar(128) NOT NULL default ''",
        ],

    ], // fields

];
