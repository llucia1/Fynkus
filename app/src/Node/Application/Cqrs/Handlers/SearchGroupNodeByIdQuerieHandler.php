<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Handlers;

use GridCP\Common\Domain\Bus\Query\QueryHandler;
use GridCP\Common\Infrastructure\OpenSSL\OpenSSLService;
use GridCP\Node\Application\Cqrs\Queries\SearchGroupNodeByIdQuerie;
use GridCP\Node\Application\Cqrs\Queries\SearchNodeByIdQuerie;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Application\Response\NodeResponses;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeNotExistError;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SearchGroupNodeByIdQuerieHandler implements QueryHandler
{



    public function __construct(private  readonly  INodeRepository $repository,
                                private readonly  OpenSSLService $openSSLService)
    {}

    public function __invoke(SearchGroupNodeByIdQuerie $ids): NodeResponses | ListNodesEmptyException
    {
        try {
            return  $this->getGroupById($ids->gets());
        }catch(ListNodesEmptyException $e){
           return new ListNodesEmptyException();
        }
    }

    public function getGroupById(array $nodes): NodeResponses | ListNodesEmptyException
    {
        $result = $this->repository->findByIds($nodes);
        if (empty($result)) throw new ListNodesEmptyException();

        return $this->toResponse($result);
    }

    public function toResponse(array $nodes): NodeResponses
    {
        return new NodeResponses( ...array_map(function($node) {  
            $pvePassword =$this->openSSLService->decrypt($node->pve_password);
            return new NodeResponse(
                $node->uuid,
                $node->gcp_node_name,
                $node->pve_node_name,
                $node->pve_hostname,
                $node->pve_username,
                $pvePassword,
                $node->pve_realm,
                $node->pve_port,
                $node->pve_ip,
                $node->ssh_port,
                $node->timezone,
                $node->keyboard,
                $node->display,
                $node->storage,
                $node->storage_iso,
                $node->storage_image,
                $node->storage_backup,
                $node->network_interface,
                $node->cpu,
                $node->getId()
            );
        }, $nodes));
    }


}