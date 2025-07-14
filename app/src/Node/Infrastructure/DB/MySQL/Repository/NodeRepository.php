<?php
declare(strict_types=1);

namespace GridCP\Node\Infrastructure\DB\MySQL\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use GridCP\Net\Common\Infrastructure\DB\MySQL\Entity\Ip4Entity;
use GridCP\Node\Domain\Exception\NodoDuplicated;
use GridCP\Node\Domain\Exception\NodoInserDBError;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;

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
class NodeRepository extends ServiceEntityRepository implements INodeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NodeEntity::class);
    }

    public function save(NodeEntity $node): void
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

    public function delete(NodeEntity $node): void
    {
        $entityManager = $this->getEntityManager();
        $node->setActive(false);
        $entityManager->persist($node);
        $entityManager->flush();
    }
    public function findByUuidWithFloatGroups(string $nodeUuid): ?NodeEntity
    {
        return $this->createQueryBuilder('n')
        ->leftJoin('n.nodeFloatGroups', 'nfg', 'WITH', 'nfg.active = true')
        ->leftJoin('nfg.floatGroup', 'fg', 'WITH', 'fg.active = true')
        ->addSelect('nfg', 'fg')
        ->where('n.uuid = :uuid AND n.active = true')
        ->setParameter('uuid', $nodeUuid)
        ->getQuery()
        ->getOneOrNullResult();

    }
    public function getAllByUuidWithFloatGroups(): ?array
    {
        return $this->createQueryBuilder('n')
        ->leftJoin('n.nodeFloatGroups', 'nfg', 'WITH', 'nfg.active = true')
        ->leftJoin('nfg.floatGroup', 'fg', 'WITH', 'fg.active = true')
        ->addSelect('nfg', 'fg')
        ->where('n.active = true')
        ->getQuery()
        ->getResult();

    }

    public function getNetworkIdsByNodeName(string $GCPName): array
    {
        $networkIds = $this->createQueryBuilder('n')
            ->select('net.id')
            ->innerJoin('n.nodeFloatGroups', 'nf2', 'WITH', 'nf2.active = true') 
            ->innerJoin('nf2.floatGroup', 'fg', 'WITH', 'fg.active = true') 
            ->innerJoin('fg.networkFloatGroups', 'nf', 'WITH', 'nf.active = true') 
            ->innerJoin('nf.network', 'net', 'WITH', 'net.active = true') 
            ->where('n.gcp_node_name = :gcpNodeName AND n.active = true')
            ->setParameter('gcpNodeName', $GCPName)
            ->getQuery()
            ->getResult();
    
        return array_column($networkIds, 'id');
    }
    
    public function findByUuid(string $uuid): ?NodeEntity
    {
        return $this->findOneBy(['uuid' => $uuid, 'active'=>true]);
    }
    public function findById(int $id): ?NodeEntity
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findOneByGCPName(string $GCPName): ?NodeEntity
    {
        return $this->findOneBy(['gcp_node_name' => $GCPName, 'active'=>true]);
    }
    public function findOneByVPEName(string $VPEName): ?NodeEntity
    {
        return $this->findOneBy(['pve_node_name' => $VPEName, 'active'=>true]);
    }
    public function findByIds(array $ids): array
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.id IN (:ids)')
            ->setParameter('ids', $ids);

        return $qb->getQuery()->getResult();
    }
    public function getAll(): array
    {
        $entityManager = $this->getEntityManager();
        return $entityManager->getRepository(NodeEntity::class)->findBy(["active"=>true]);
    }
}