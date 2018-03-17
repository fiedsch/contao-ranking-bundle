<?php

declare(strict_types=1);

/**
 * @author Andreas Fieger
 */
namespace Fiedsch\RankingBundle\ContaoManager;

use Fiedsch\RankingBundle\FiedschRankingBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(FiedschRankingBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}