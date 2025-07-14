<?php
declare(strict_types=1);
namespace GridCP\Proxmox\Os\Application\Cqrs\Handlers;


use GridCP\Common\Domain\Bus\Query\QueryHandler;
use GridCP\Proxmox\Os\Application\Cqrs\Queries\getOsEntityByNameQueried;
use GridCP\Proxmox\Os\Application\Response\OsResponsesQuery;
use GridCP\Proxmox\Os\Domain\Exception\ListOsEmptyException;
use GridCP\Proxmox\Os\Domain\Repository\IOsRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class getOsEntityByNameHandler implements QueryHandler
{


    public function __construct(private  readonly  IOsRepository $osService)
    {
    }


    public function __invoke(getOsEntityByNameQueried $os): OsResponsesQuery
    {
        try {
            $oss = $this->getOsByName($os->name());
            $result = new OsResponsesQuery( $oss );
            return $result;

        }catch(\Exception $ex){
            throw new ListOsEmptyException($ex);
        }
    }

    public function getOsByName(String $osName)
    {
        return $this->osService->findByName( $osName );
    }


}