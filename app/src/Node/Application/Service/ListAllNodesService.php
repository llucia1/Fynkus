<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Service;

use GridCP\Node\Application\Response\CpuResponse;
use GridCP\Node\Application\Response\Floatgroupesponse;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Application\Response\NodeResponses;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Domain\Service\IListNodeService;
use Psr\Log\LoggerInterface;

readonly class ListAllNodesService implements IListNodeService
{
    public function __construct(private INodeRepository $nodeRepository, private LoggerInterface $logger)
    {
    }

    public function __invoke():NodeResponses
    {
        return $this->getAll();
    }

    public function getAll():NodeResponses
    {
        $this->logger->info('Start Gel All Nodes');
        $nodes = $this->nodeRepository->getAllByUuidWithFloatGroups();
        
        return empty($nodes)
            ? throw new ListNodesEmptyException()
            : $this->toResponse($nodes);
    }

    public function toResponse(array $nodes):NodeResponses
    {
        return new NodeResponses(
            ...array_map(function($result) {
                
                if (!is_null($result->getCpu()))
                {
                    $cpu = array();
                    array_key_exists('name', $result->getCpu()) ? $cpu['name'] = $result->getCpu()['name'] : $cpu['name'] = null;
                    array_key_exists('vendor', $result->getCpu()) ? $cpu['vendor'] = $result->getCpu()['vendor'] : $cpu['vendor'] = null;
                    array_key_exists('custom', $result->getCpu()) ? $cpu['custom'] = $result->getCpu()['custom'] : $cpu['custom'] = null;
                }
                return new NodeResponse(
                    $result->getUuid(),
                    $result->getGcpName(),
                    $result->getPveName(),
                    $result->getPveHostName(),
                    $result->getPveUserName(),
                    $result->getPvePassword(),
                    $result->getPveRealm(),
                    $result->getPvePort(),
                    $result->getPveIp(),
                    $result->getSshPort(),
                    is_null($result->getTimezone())? null : $result->getTimezone(),
                    is_null($result->getKeyboard())? null: $result->getKeyboard(),
                    is_null($result->getDisplay())?null: $result->getDisplay(),
                    is_null($result->getStorage())?null: $result->getStorage(),
                    is_null($result->getStorageIso())?null: $result->getStorageIso(),
                    is_null($result->getStorageImage())?null: $result->getStorageImage(),
                    is_null($result->getStorageBackup())?null: $result->getStorageBackup(),
                    is_string($result->getNetworkInterface()) ? $result->getNetworkInterface() : null,
                    is_null($result->getCpu()) ? null : $cpu,
                    null,
                    is_null($result->getPriority()) ? null : $result->getPriority(),
                    $result->getFloatGroups() ? array_map(
                        fn($relation) => new Floatgroupesponse(
                            $relation->getFloatGroup()->getUuid(),
                            $relation->getFloatGroup()->getName()
                        ),
                        $result->getFloatGroups()->toArray()
                    ) : null,
                    $result->getOs() ? $result->getOs()->getName() : null
                );
            }, $nodes)
        );
    }
}