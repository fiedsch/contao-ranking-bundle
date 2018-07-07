<?php

/**
 * @package Ligaverwaltung
 * @link https://github.com/fiedsch/contao-ligaverwaltung-bundle/
 * @license https://opensource.org/licenses/MIT
 */

namespace Fiedsch\RankingBundle\Helper;

use Contao\Image;
use Contao\DataContainer;

class DCAHelper
{


    /**
     * Button um das zum RankingResult gehörigen RankingPlayer in einem Modal-Window
     * bearbeiten zu können ('wizard' in tl_rankingresult)
     *
     * @param DataContainer $dc
     * @return string
     */
    public static function editPlayerWizard(DataContainer $dc)
    {
        $result = '';
        if ($dc->value > 0) {
        // http://edart-bayern.de-c4.localhost/contao?do=ranking.spieler&ref=PiV6JIbz
        $result .= 
            // gewählten Spieler bearbeiten
            '<a href="contao/main.php?do=ranking.spieler&amp;act=edit&amp;id=' . $dc->value
            . '&amp;popup=1&amp;rt=' . REQUEST_TOKEN
            . '" title="' . specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1]) . '"'
            . ' style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''
            . specialchars(str_replace("'", "\\'", specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1])))
            . '\',\'url\':this.href});return false">'
            . Image::getHtml('alias.svg', $GLOBALS['TL_LANG']['tl_spieler']['editmember'][1], 'style="vertical-align:top"')
            . '</a>';
        }
        $result .= 
            // neuen Spieler anlegen
            // http://edart-bayern.de-c4.localhost/app_dev.php/contao?do=ranking.spieler&ref=wTYQGfF3
            '<a href="contao/main.php?do=ranking.spieler&amp;popup=1&amp;rt=' . REQUEST_TOKEN
            . '" title="' . specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1]) . '"'
            . ' style="padding-left:3px" onclick="Backend.openModalIframe({\'width\':768,\'title\':\''
            . specialchars(str_replace("'", "\\'", specialchars($GLOBALS['TL_LANG']['tl_spieler']['editmember'][1])))
            . '\',\'url\':this.href});return false">'
            . Image::getHtml('new.svg', $GLOBALS['TL_LANG']['tl_spieler']['editmember'][1], 'style="vertical-align:top"')
            . '</a>'
        ;
        return $result;
    }

}
