<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Service;

use Psr\Log\LoggerInterface;
use GridCP\Node\Application\Response\Floatgroupesponse;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Domain\Service\IListNodeByUuidService;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;

class ListNodeByUuidservice implements IListNodeByUuidService
{
    public function __construct(private readonly INodeRepository $nodeRepository, private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(string $uuid): NodeResponse
    {
        return $this->getNode($uuid);
    }

    public function getNode(string $uuid): NodeResponse
    {
        $this->logger->info('Start Gel  Node by uuid');
        $existingNode = $this->nodeRepository->findByUuidWithFloatGroups($uuid);
        return is_null($existingNode)
              ? throw new ListNodesEmptyException()
              : $this->toResponse($existingNode);
    }

    public function toResponse(NodeEntity $nodeEntity): NodeResponse
    {
        if (!is_null($nodeEntity->getCpu()))
        {
            $cpu = array();
            array_key_exists('name', $nodeEntity->getCpu()) ? $cpu['name'] = $nodeEntity->getCpu()['name'] : $cpu['name'] = null;
            array_key_exists('vendor', $nodeEntity->getCpu()) ? $cpu['vendor'] = $nodeEntity->getCpu()['vendor'] : $cpu['vendor'] = null;
            array_key_exists('custom', $nodeEntity->getCpu()) ? $cpu['custom'] = $nodeEntity->getCpu()['custom'] : $cpu['custom'] = null;
        }
        return new NodeResponse(
            $nodeEntity->getUuid(),
            $nodeEntity->getGcpName(),
            $nodeEntity->getPveName(),
            $nodeEntity->getPveHostName(),
            $nodeEntity->getPveUserName(),
            $nodeEntity->getPvePassword(),
            $nodeEntity->getPveRealm(),
            $nodeEntity->getPvePort(),
            $nodeEntity->getPveIp(),
            $nodeEntity->getSshPort(),
            is_null($nodeEntity->getTimezone())? null : $nodeEntity->getTimezone(),
            is_null($nodeEntity->getKeyboard())? null: $nodeEntity->getKeyboard(),
            is_null($nodeEntity->getDisplay())?null: $nodeEntity->getDisplay(),
            is_null($nodeEntity->getStorage())?null: $nodeEntity->getStorage(),
            is_null($nodeEntity->getStorageIso())?null: $nodeEntity->getStorageIso(),
            is_null($nodeEntity->getStorageImage())?null: $nodeEntity->getStorageImage(),
            is_null($nodeEntity->getStorageBackup())?null: $nodeEntity->getStorageBackup(),
            is_string($nodeEntity->getNetworkInterface()) ? $nodeEntity->getNetworkInterface() : null,
            is_null($nodeEntity->getCpu()) ? null : $cpu,
            null,
            is_null($nodeEntity->getPriority()) ? null : $nodeEntity->getPriority(),
            $nodeEntity->getFloatGroups() ? array_map(
                fn($relation) => new Floatgroupesponse(
                    $relation->getFloatGroup()->getUuid(),
                    $relation->getFloatGroup()->getName()
                ),
                $nodeEntity->getFloatGroups()->toArray()
            ) : null,
            $nodeEntity->getOs() ? $nodeEntity->getOs()->getName() : null
            
        );
    }
}