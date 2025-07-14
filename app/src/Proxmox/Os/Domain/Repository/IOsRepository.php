<?php
declare(strict_types=1);
namespace GridCP\Proxmox\Os\Domain\Repository;

use GridCP\Proxmox\Os\Infrastructure\DB\MySQL\Entity\OsEntity;

interface IOsRepository
{
    public function save(OsEntity $so): void;

    public function findByUuid(string $uuid): ?OsEntity;
    public function findByName(string $name): ?OsEntity;
    public function getAll(): array;
}