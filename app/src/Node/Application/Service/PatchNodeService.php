<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Service;

use Exception;
use GridCP\Node\Application\Helpers\FloatgoupsTrait;
use GridCP\Common\Domain\Bus\Query\QueryBus;
use GridCP\Node\Domain\Exception\NodeNotExistError;
use GridCP\Node\Domain\Exception\NodoDuplicated;
use GridCP\Node\Domain\Repository\INodeFloatgroupRepository;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Domain\Service\IPatchNodeService;
use GridCP\Node\Domain\VO\Node;
use GridCP\Proxmox\Os\Application\Cqrs\Queries\getOsEntityByNameQueried;
use Psr\Log\LoggerInterface;
readonly class PatchNodeService implements IPatchNodeService
{
    use FloatgoupsTrait;
    public function __construct(
        private INodeRepository $nodeRepository,
        private INodeFloatgroupRepository $nodeFloatgroupRepository,
        private LoggerInterface $logger,
        private QueryBus             $queryBus,
    ){}

  public function __invoke( Node $node):void
  {
        $this->update($node);
  }


   public function update(Node $node):void
    {
            $this->logger->info("Update Patch Service Node ->" . $node->uuid()->value());
            $nodeEntity = $this->nodeRepository->findByUuid($node->uuid()->value());
            if (!$nodeEntity ){
                $this->logger->error("Error Node Not Exist");
                throw new NodeNotExistError;
            } else if (!$nodeEntity->isActive()){
                $this->logger->error("Error Node Not Exist");
                throw new NodeNotExistError;
            }

            if ($node->node_gcp_name()) {
                $existingNode = $this->nodeRepository->findOneByGCPName($node->node_gcp_name()->value());
                if ($existingNode && $existingNode->getUuid() !== $nodeEntity->getUuid() ) {
                    throw new NodoDuplicated($node->node_gcp_name()->value());
                }
                $nodeEntity->setGcpName( $node->node_gcp_name()->value() );
            }
            if ($node->node_vpe_name()) {
                $existingNode = $this->nodeRepository->findOneByVPEName($node->node_vpe_name()->value());
                if ($existingNode && $existingNode->getUuid() !== $nodeEntity->getUuid() ) {
                    throw new NodoDuplicated($node->node_vpe_name()->value());
                }
                $nodeEntity->setPveName( $node->node_vpe_name()->value() );
            }

            if ($node->osName()) {
                $os = $this->queryBus->ask(new getOsEntityByNameQueried($node->osName()->value()) );
                $osEntity = $os->get();
                if (!$osEntity  || $osEntity instanceof Exception) {
                    $osEntity = null;
                }
            }
            
            !is_null($node->vpe_ip()) ? $nodeEntity->setPveIp($node->vpe_ip()->value()) : null;
            !is_null($node->vpe_hostname()) ? $nodeEntity->setPveHostName($node->vpe_hostName()->value()):null;
            !is_null($node->vpe_username()) ? $nodeEntity->setPveUserName($node->vpe_username()->value()):null;
            !is_null($node->vpe_password()) ? $nodeEntity->setPvePassword($node->vpe_password()->value()):null;
            !is_null($node->vpe_realm()) ? $nodeEntity->setPveRealm($node->vpe_realm()->value()):null;
            !is_null($node->vpe_port()) ? $nodeEntity->setPvePort($node->vpe_port()->value()):null;
            !is_null($node->sshPort()) ? $nodeEntity->setSshPort($node->sshPort()->value()):null;
            !is_null($node->timeZone()) ? $nodeEntity->setTimezone($node->timeZone()->value()):null;
            !is_null($node->keyboard()) ? $nodeEntity->setKeyboard($node->keyboard()->value()):null;
            !is_null($node->display()) ? $nodeEntity->setDisplay($node->display()->value()):null;
            !is_null($node->storage()) ? $nodeEntity->setStorage($node->storage()->value()):null;
            !is_null($node->storageIso()) ? $nodeEntity->setStorageIso($node->storageIso()->value()):null;
            !is_null($node->storageImage()) ? $nodeEntity->setStorageImage($node->storageImage()->value()):null;
            !is_null($node->storageBackup()) ? $nodeEntity->setStorageBackup($node->storageBackup()->value()):null;
            !is_null($node->networkInterface()) ? $nodeEntity->setNetworkInterface($node->networkInterface()->value()):null;
            !is_null($node->cpu()) ? $nodeEntity->setCpu( $node->cpu()->value()):null;
            !is_null($node->priority()) ? $nodeEntity->setPriority( $node->priority()->value() ):null;
            !is_null($node->osName()) ? $nodeEntity->setOs( $osEntity ):null;

            if ($node->floatgroupsUuid()) {
                $floatgroups = $this->findFloatgroups($node->floatgroupsUuid(), $this->queryBus);
                $this->disableAllNodeFloatgroups($nodeEntity, $this->nodeFloatgroupRepository);
                $this->saveAllFloatgroups($floatgroups, $nodeEntity, $this->nodeFloatgroupRepository);
            }
            $this->nodeRepository->save($nodeEntity);
         
    }
}



