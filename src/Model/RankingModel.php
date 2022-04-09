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

namespace Fiedsch\RankingBundle\Model;

use Contao\Model;

class RankingModel extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected static $strTable = 'tl_ranking';
}
