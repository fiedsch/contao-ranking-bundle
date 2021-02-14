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

$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{ranking_legend},ranking_pott_betrag';

$GLOBALS['TL_DCA']['tl_settings']['fields']['ranking_pott_betrag'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['ranking_pott_betrag'],
    'inputType' => 'text',
    'eval' => ['tl_class' => 'w50', 'rgxp' => 'digit'],
];
