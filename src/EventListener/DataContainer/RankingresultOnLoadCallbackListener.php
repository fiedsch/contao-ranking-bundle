<?php

namespace Fiedsch\RankingBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

#[AsCallback(table: 'tl_rankingresult', target: 'config.oncreate')]
class RankingresultOnLoadCallbackListener
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(string $table, int $insertId, array $fields, DataContainer $dc): void
    {
        $newValue = $this->connection->executeQuery('SELECT MAX(platz)+1 FROM tl_rankingresult WHERE pid = ?', [$fields['pid']])->fetchOne();
        $this->connection->executeStatement('UPDATE tl_rankingresult SET platz = ? WHERE id = ?', [$newValue, $insertId]);
    }

}