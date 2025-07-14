<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Handlers;

use GridCP\Common\Domain\Bus\Query\QueryHandler;
use GridCP\Common\Infrastructure\OpenSSL\OpenSSLService;
use GridCP\Node\Application\Cqrs\Queries\SearchNodeByNameQuerie;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeNotExistError;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SearchNodeByNameQuerieHandler implements QueryHandler
{



    public function __construct(private  readonly  INodeRepository $repository,
                                private readonly  OpenSSLService $openSSLService)
    {
      }

    public function __invoke(SearchNodeByNameQuerie $node): NodeResponse|NodeNotExistError|null
    {
        try {
            return  $this->getNodeByName($node->name());
        }catch(\Exception $ex){
           throw new ListNodesEmptyException();
        }
    }

    public function getNodeByName(String $node): NodeResponse|NodeNotExistError|null
    {
        $result = $this->repository->findOneByGCPName($node);
        if (is_null($result)) { return new NodeNotExistError();}
        return $this->toResponse($result);

    }

    public function toResponse(NodeEntity $nodeEntity): NodeResponse
    {
           $password = $this->openSSLService->decrypt($nodeEntity->pve_password);
           return new NodeResponse(
                $nodeEntity->uuid,
                $nodeEntity->gcp_node_name,
                $nodeEntity->pve_node_name,
                $nodeEntity->pve_hostname,
                $nodeEntity->pve_username,
                $password,
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
                $nodeEntity->getId(),
                $nodeEntity->priority ? $nodeEntity->priority : null,
                $nodeEntity->getFloatGroups() ? $nodeEntity->getFloatGroups()->toArray() : null,
                $nodeEntity->getOs() ? $nodeEntity->getOs() : null
                
            );
    }


}