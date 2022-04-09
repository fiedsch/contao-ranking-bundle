<?php

namespace Fiedsch\RankingBundle\Helper;

class PunkteHelper implements PunkeHelperInterface
{
    public function getPunkte($platz, $teilnehmerzahl): int
    {
        // https://www.munich-darts-challenge.de/?pageIdx=7
        return max(232 - (200 * $platz / $teilnehmerzahl), 40);
    }
}