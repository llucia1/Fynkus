<?php
declare(strict_types=1);
namespace GridCP\Proxmox\Os\Application\Cqrs\Handlers;


use GridCP\Common\Domain\Bus\Query\QueryHandler;
use GridCP\Proxmox\Os\Application\Cqrs\Queries\getOsByNameQueried;
use GridCP\Proxmox\Os\Application\Response\OsResponse;
use GridCP\Proxmox\Os\Application\Response\OsResponsesQuery;
use GridCP\Proxmox\Os\Domain\Exception\ListOsEmptyException;
use GridCP\Proxmox\Os\Domain\Repository\IOsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class getOsByNameHandler implements QueryHandler
{


    public function __construct(private  readonly  IOsRepository $osService)
    {
    }


    public function __invoke(getOsByNameQueried $os): OsResponsesQuery
    {
        try {
            $oss = $this->getOsByName($os->name());
            $result = new OsResponsesQuery( $oss ? $this->toResponse($oss) : null);
            return $result;

        }catch(\Exception $ex){
            throw new ListOsEmptyException($ex);
        }
    }

    public function getOsByName(String $osName)
    {
        return $this->osService->findByName( $osName );
    }
    public function toResponse( $response):OsResponse
    {
        return new OsResponse( 
            $response->getUuid(),
            $response->getName(),
            $response->getTag(),
            $response->getImage(),
            $response->getUsername(),
            $response->getId()
         );

    }


}