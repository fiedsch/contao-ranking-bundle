<?php

namespace Fiedsch\RankingBundle\Helper;

interface PunkeHelperInterface
{
    public function getPunkte($platz, $teilnehmerzahl): int;
}