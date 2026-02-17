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

namespace Fiedsch\RankingBundle\Helper;

use Contao\DataContainer;
use Contao\Image;
use Contao\System;
use Contao\StringUtil;

class DCAHelper
{
    /**
     * Button um das zum RankingResult gehörigen RankingPlayer in einem Modal-Window
     * bearbeiten zu können ('wizard' in tl_rankingresult).
     */
    public static function editPlayerWizard(DataContainer $dc): string
    {
        $result = '';
        $requestToken = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();

        if ($dc->value > 0) {
            // http://edart-bayern.de-c4.localhost/contao?do=ranking_spieler&ref=PiV6JIbz
            $result .=
            // gewählten Spieler bearbeiten
            '<a href="contao/?do=ranking_spieler&amp;act=edit&amp;id='.$dc->value
            .'&amp;popup=1&amp;rt='.$requestToken
            .'" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1] ?? '').'"'
            .' style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''
            .StringUtil::specialchars(str_replace("'", "\\'", StringUtil::specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1] ?? '')))
            .'\',\'url\':this.href});return false">'
            .Image::getHtml('alias.svg', $GLOBALS['TL_LANG']['tl_spieler']['editmember'][1] ?? '', 'style="vertical-align:top"')
            .'</a>';
        }
        $requestToken = System::getContainer()->get('contao.csrf.token_manager')->getDefaultTokenValue();
        $result .=
            // neuen Spieler anlegen
            // http://edart-bayern.de-c4.localhost/app_dev.php/contao?do=ranking_spieler&ref=wTYQGfF3
            '<a href="contao/?do=ranking_spieler&amp;popup=1&amp;rt='.$requestToken
            .'" title="'.StringUtil::specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1] ?? '').'"'
            .' style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''
            .StringUtil::specialchars(str_replace("'", "\\'", StringUtil::specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1] ?? '')))
            .'\',\'url\':this.href});return false">'
            .Image::getHtml('new.svg', $GLOBALS['TL_LANG']['tl_spieler']['editmember'][1] ?? '', 'style="vertical-align:top"')
            .'</a>'
        ;

        return $result;
    }
}
