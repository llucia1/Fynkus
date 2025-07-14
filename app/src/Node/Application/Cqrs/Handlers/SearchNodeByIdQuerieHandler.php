<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Handlers;

use GridCP\Common\Domain\Bus\Query\QueryHandler;
use GridCP\Common\Infrastructure\OpenSSL\OpenSSLService;
use GridCP\Node\Application\Cqrs\Queries\SearchNodeByIdQuerie;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeNotExistError;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SearchNodeByIdQuerieHandler implements QueryHandler
{



    public function __construct(private  readonly  INodeRepository $repository,
                                private readonly  OpenSSLService $openSSLService)
    {}

    public function __invoke(SearchNodeByIdQuerie $node): NodeResponse | NodeNotExistError 
    {
        try {
            return  $this->getFindById($node->id());
        }catch(NodeNotExistError $e){
           return new NodeNotExistError();
        }
    }

    public function getFindById(int $node): NodeResponse | NodeNotExistError
    {
        $result = $this->repository->findById($node);
        if (is_null($result)) throw new NodeNotExistError();
        $result->setPvePassword($this->openSSLService->decrypt($result->pve_password));
        return $this->toResponse($result);
    }

    public function toResponse(NodeEntity $nodeEntity): NodeResponse
    {
           $result = new NodeResponse(
                $nodeEntity->uuid,
                $nodeEntity->gcp_node_name,
                $nodeEntity->pve_node_name,
                $nodeEntity->pve_hostname,
                $nodeEntity->pve_username,
                $nodeEntity->pve_password,
                $nodeEntity->pve_realm,
                $nodeEntity->pve_port,
                $nodeEntity->pve_ip,
                $nodeEntity->ssh_port,
                $nodeEntity->timezone,
                $nodeEntity->keyboard,
                $nodeEntity->display,
                $nodeEntity->storage,
                $nodeEntity->storage_iso,
                $nodeEntity->storage_image,
                $nodeEntity->storage_backup,
                $nodeEntity->network_interface,
                $nodeEntity->cpu,
                $nodeEntity->getId()
            );
            return  $result;
    }


}