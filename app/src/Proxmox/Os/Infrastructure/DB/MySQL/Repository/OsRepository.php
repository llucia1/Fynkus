<?php
declare(strict_types=1);

namespace GridCP\Proxmox\Os\Infrastructure\DB\MySQL\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use GridCP\Proxmox\Os\Domain\Repository\IOsRepository;
use GridCP\Proxmox\Os\Infrastructure\DB\MySQL\Entity\OsEntity;

/**
 * @extends ServiceEntityRepository<OsEntity>
 *
 * @implements IOsRepository<OsEntity>
 *
 * @method OsEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method OsEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method OsEntity[]    findAll()
 * @method OsEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OsRepository extends ServiceEntityRepository implements IOsRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OsEntity::class);
    }
    public function save(OsEntity $so): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($so);
        $entityManager->flush();
    }

    public function findByUuid(string $uuid): ?OsEntity
    {
        return $this->findOneBy(['uuid'=>$uuid]);
    }
    public function findByName(string $name): ?OsEntity
    {
        return $this->findOneBy(['name' => $name]);
    }
    public function findById(string $id): ?OsEntity
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getAll(): array
    {
        return $this->findAll();
    }
}

