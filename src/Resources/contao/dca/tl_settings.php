<?php

/**
 * @package Ligaverwaltung
 * @link https://github.com/fiedsch/contao-ligaverwaltung-bundle/
 * @license https://opensource.org/licenses/MIT
 */

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{ranking_legend},ranking_pott_betrag';

$GLOBALS['TL_DCA']['tl_settings']['fields']['ranking_pott_betrag'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['ranking_pott_betrag'],
    'inputType' => 'text',
    'eval'      => ['tl_class' => 'w50','rgxp'=>'digit'],
];
