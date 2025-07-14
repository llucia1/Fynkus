<?php
declare(strict_types=1);

namespace GridCP\Node\Infrastructure\DB\MySQL\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use GridCP\Node\Domain\Exception\NodoDuplicated;
use GridCP\Node\Domain\Exception\NodoInserDBError;
use GridCP\Node\Domain\Repository\INodeFloatgroupRepository;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeFloatGroupEntity;

/**
 * @extends ServiceEntityRepository<NodeEntity>
 *
 * @implements INodeRepository<NodeEntity>
 *
 * @method NodeEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method NodeEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method NodeEntity[]    findAll()
 * @method NodeEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NodeFloatGroupRepository extends ServiceEntityRepository implements INodeFloatgroupRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeFloatGroupEntity::class);
    }

    public function save(NodeFloatGroupEntity $node): void
    {
        $entityManager = $this->getEntityManager();


        try {
            $entityManager->persist($node);
            $entityManager->flush();



        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            throw new NodoDuplicated($e->getMessage());
        } catch (\Exception $e) {
            throw new NodoInserDBError($e->getMessage());
        }



    }

    public function findByNodeIdAndFloatgroupId(int $nodeId, int $floatgroupId): ?NodeFloatGroupEntity
    {
        return $this->findOneBy(['node' => $nodeId, 'floatGroup' => $floatgroupId, 'active'=>true]);    
    }
    public function findAllByNodeId(int $nodeId): ?array
    {
        return $this->findBy(['node' => $nodeId, 'active'=>true]);    
    }
    
    public function getAll(): array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(NodeFloatGroupEntity::class)->findBy(["active"=>true]);
    }
}