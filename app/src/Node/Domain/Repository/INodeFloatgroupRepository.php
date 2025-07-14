<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\Repository;

use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeFloatGroupEntity;

interface INodeFloatgroupRepository
{
    public function save(NodeFloatGroupEntity $node): void;
    public function findByNodeIdAndFloatgroupId(int $nodeUuid, int $floatgroupUuid): ?NodeFloatGroupEntity;
    public function findAllByNodeId(int $nodeId): ?array;
    public function getAll(): array;

}