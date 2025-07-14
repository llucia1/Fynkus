<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\Repository;

use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;

interface INodeRepository
{
    public function save(NodeEntity $node): void;
    public function delete(NodeEntity $node): void;
    public function findOneByGCPName(string $GCPName): ?NodeEntity;
    public function getNetworkIdsByNodeName(string $GCPName): array;
    public function findOneByVPEName(string $VPEName): ?NodeEntity;
    public function findByUuidWithFloatGroups(string $nodeUuid): ?NodeEntity;
    public function getAllByUuidWithFloatGroups(): ?array;
    public function findByUuid(string $uuid): ?NodeEntity;
    public function findById(int $id): ?NodeEntity;
    public function findByIds(array $ids): array;
    public function getAll(): array;

}