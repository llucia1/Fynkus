<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Helpers;
use GridCP\Common\Domain\Bus\Query\QueryBus;
use GridCP\Net\Ip4FloatGroup\Application\Cqrs\Queries\GetFloatGroupEntityByUuidQueried;
use GridCP\Node\Domain\Repository\INodeFloatgroupRepository;
use GridCP\Node\Domain\VO\FloatgroupsUuids;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeFloatGroupEntity;



trait FloatgoupsTrait
{
    public function findFloatgroups( ?FloatgroupsUuids $floatgroupsUuids, QueryBus             $queryBus ): array 
    {

      $result = [];
      foreach ($floatgroupsUuids->get() as $floatgroupUuid) {
        try{
            $floatgroup = $queryBus->ask(new GetFloatGroupEntityByUuidQueried($floatgroupUuid->value()) );
            $result[] = $floatgroup->get();
        }catch(\Exception $ex){ }
      }

      return $result;
    }
    public function disableAllNodeFloatgroups( NodeEntity $nodeEntity, INodeFloatgroupRepository $nodeFloatgroupRepository ): void 
    {
      $all = $nodeFloatgroupRepository->findAllByNodeId($nodeEntity->getId());
      foreach ($all as $nodeFloatgroup) {
          if ($nodeFloatgroup) {
              $nodeFloatgroup->setActive(false);
              $nodeFloatgroupRepository->save($nodeFloatgroup);
            }
      }
    }
    public function saveAllFloatgroups( array $floatgroups, NodeEntity $nodeEntity, INodeFloatgroupRepository $nodeFloatgroupRepository ): void 
    {
      foreach ($floatgroups as $floatgroup) {
          if ($floatgroup) {
            $nodeFloatgroup = new NodeFloatGroupEntity();
            $nodeFloatgroup->setFloatgroup($floatgroup);
            $nodeFloatgroup->setNode($nodeEntity);

            $nodeFloatgroupRepository->save($nodeFloatgroup);
          }
      }
    }
    

}