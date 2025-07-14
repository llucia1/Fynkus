<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Handlers;

use GridCP\Common\Domain\Bus\Query\QueryHandler;
use GridCP\Common\Infrastructure\OpenSSL\OpenSSLService;
use GridCP\Node\Application\Cqrs\Queries\SearchAllNetworkOfOneNodeByNameQuerie;
use GridCP\Node\Application\Response\AllIpNodeResponses;
use GridCP\Node\Application\Response\AllNetworksNodeResponses;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeNotExistError;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;
use GridCP\Node\Infrastructure\DB\MySQL\Repository\NodeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SearchAllNetworkOfOneNodeByNameQuerieHandler implements QueryHandler
{



    public function __construct(private  readonly  NodeRepository $repository,
                                private readonly  OpenSSLService $openSSLService)
    {
      }

    public function __invoke(SearchAllNetworkOfOneNodeByNameQuerie $node): AllNetworksNodeResponses | NodeNotExistError
    {
        try {
            return  $this->getAllIpsNodeByName($node->name());
        }catch(\Exception $ex){
           throw new ListNodesEmptyException();
        }
    }

    public function getAllIpsNodeByName(String $nodeGcp): AllNetworksNodeResponses | NodeNotExistError
    {
        
        $result = $this->repository->getNetworkIdsByNodeName($nodeGcp);
        if (is_null($result)) { return new NodeNotExistError();}
        return new AllNetworksNodeResponses($result);

    }


}