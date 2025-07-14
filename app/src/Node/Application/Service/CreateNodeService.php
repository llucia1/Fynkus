<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Service;

use Exception;
use GridCP\Common\Domain\Bus\EventSource\EventBus;
use GridCP\Common\Domain\Bus\Query\QueryBus;
use GridCP\Node\Application\Helpers\FloatgoupsTrait;
use GridCP\Node\Domain\Exception\NodoDuplicatedException;
use GridCP\Node\Domain\Repository\INodeFloatgroupRepository;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Domain\Service\iCreateNodeService;
use GridCP\Node\Domain\VO\Node;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;
use GridCP\Proxmox\Os\Application\Cqrs\Queries\getOsEntityByNameQueried;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Psr\Log\LoggerInterface;

readonly class CreateNodeService implements iCreateNodeService
{
    use FloatgoupsTrait;
    public function __construct(
        private INodeRepository $nodeRepository,
        private INodeFloatgroupRepository $nodeFloatgroupRepository,
        private QueryBus             $queryBus,
        private readonly LoggerInterface $logger,
        private EventBus $bus
    ) {}

    public function __invoke(Node $node): string
    {
        return $this->create($node);
    }

    public function create(Node $node):string
    {
            $this->logger->info('Start Create a one Node');
        $existingNode = $this->nodeRepository->findOneByGCPName($node->node_gcp_name()->value());

        if ($existingNode) {
            throw new NodoDuplicatedException($node->node_gcp_name()->value());
        }


        $os = $this->queryBus->ask(new getOsEntityByNameQueried($node->osName()->value()) );
        $osEntity = $os->get();
        if (!$osEntity  || $osEntity instanceof Exception) {
            $osEntity = null;
        }


        $nodeEntity = new NodeEntity();
        $nodeEntity->setUuid($node->uuid()->value());
        $nodeEntity->setGcpName($node->node_gcp_name()->value());
        $nodeEntity->setPveName($node->node_vpe_name()->value());
        $nodeEntity->setPveHostName($node->vpe_hostName()->value());
        $nodeEntity->setPveUserName($node->vpe_username()->value());
        $nodeEntity->setOs($osEntity);
        $nodeEntity->setPvePassword($node->vpe_password()->value());
        $nodeEntity->setPveRealm($node->vpe_realm()->value());
        $nodeEntity->setPvePort($node->vpe_port()->value());
        $nodeEntity->setPveIp($node->vpe_ip()->value());
        $nodeEntity->setSshPort($node->sshPort()->value());
        $nodeEntity->setPriority($node->priority()->value());

        try {

            $this->nodeRepository->save($nodeEntity);
            $floatgroups = $this->findFloatgroups($node->floatgroupsUuid(), $this->queryBus);
            $this->saveAllFloatgroups($floatgroups, $nodeEntity, $this->nodeFloatgroupRepository);

            $this->bus->publish(...$node->pullDomainEvents());
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'General error' . $e->getMessage());
        }
        
        return $nodeEntity->getUuid();
    }


}