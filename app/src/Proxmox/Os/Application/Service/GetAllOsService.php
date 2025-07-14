<?php
declare(strict_types=1);

namespace GridCP\Proxmox\Os\Application\Service;

use Psr\Log\LoggerInterface;
use GridCP\Proxmox\Os\Domain\Repository\IOsRepository;
use GridCP\Proxmox\Os\Application\Response\OsResponse;
use GridCP\Proxmox\Os\Application\Response\OsResponses;
use GridCP\Proxmox\Os\Domain\Exception\ListOsEmptyException;
use GridCP\Proxmox\Os\Infrastructure\DB\MySQL\Entity\OsEntity;

use function Lambdish\Phunctional\map;

class GetAllOsService
{
    public function __construct(
        private readonly IOsRepository $soRepository,
        public LoggerInterface       $logger

    ) {}

    public function __invoke(): OsResponses
    {
        return $this->getAll();
    }

    public function getAll(): OsResponses
    {
        $this->logger->info("Start Service Get All OS.");
        $oss =  $this->soRepository->getAll();

        return  empty($oss)
            ?throw new ListOsEmptyException()
            :new OsResponses( ...map($this->toResponse() , $oss) );
    }

    public function toResponse():callable
    {
        return static fn (OsEntity $so): OsResponse => new OsResponse(
            $so->getUuid(), 
            $so->getName(),
            $so->getTag(),
            $so->getImage(),
        );
    }
}
