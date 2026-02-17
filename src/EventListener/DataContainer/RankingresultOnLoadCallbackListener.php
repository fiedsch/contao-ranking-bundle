<?php

namespace Fiedsch\RankingBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

#[AsCallback(table: 'tl_rankingresult', target: 'config.oncreate')]
readonly class RankingresultOnLoadCallbackListener
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function __invoke(string $table, int $insertId, array $fields, DataContainer $dc): void
    {
        if (!isset($fields['pid'])) {
            $newValue = 1;
        } else {
            $newValue = $this->connection->executeQuery('SELECT MAX(platz)+1 FROM tl_rankingresult WHERE pid = ?', [$fields['pid']])->fetchOne();
        }
        $this->connection->executeStatement('UPDATE tl_rankingresult SET platz = ? WHERE id = ?', [$newValue, $insertId]);
    }

}