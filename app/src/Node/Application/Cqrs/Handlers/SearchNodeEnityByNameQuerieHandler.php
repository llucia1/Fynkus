<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Handlers;

use GridCP\Common\Domain\Bus\Query\QueryHandler;
use GridCP\Common\Infrastructure\OpenSSL\OpenSSLService;
use GridCP\Node\Application\Cqrs\Queries\SearchNodeByNameQuerie;
use GridCP\Node\Application\Cqrs\Queries\SearchNodeEntityByUuidQuerie;
use GridCP\Node\Application\Response\NodeEntityResponse;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeNotExistError;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class SearchNodeEnityByNameQuerieHandler implements QueryHandler
{



    public function __construct(private  readonly  INodeRepository $repository,
                                private readonly  OpenSSLService $openSSLService)
    {
      }

    public function __invoke(SearchNodeEntityByUuidQuerie $node): NodeEntityResponse|NodeNotExistError
    {
        try {
            return new NodeEntityResponse( $this->repository->findByUuid($node->uuid()) );
        }catch(\Exception $ex){
           throw new NodeNotExistError();
        }
    }
         


}