<?php
declare(strict_types=1);
namespace GridCP\Node\Application\EventSource\Handlers;

use Error;
use GridCP\Common\Domain\Bus\EventSource\DomainEventSubscriber;

use GridCP\Node\Infrastructure\DB\MySQL\Repository\NodeRepository;
use GridCP\Proxmox\Node\Domain\EventSource\Event\GetNodeInformationDomainEvent;
use Psr\Log\LoggerInterface;

final class GetNodeInformationListener implements  DomainEventSubscriber
{
    public function  __construct(private readonly LoggerInterface $logger, private readonly  NodeRepository $nodeRepository){

    }

    public static function subscribedTo(): array
    {
        return [GetNodeInformationDomainEvent::class];
    }

    public  function __invoke(GetNodeInformationDomainEvent $event):?array{
        try{
            $this->logger->info("Get Info for UserName ->" . $event->nodeName());
            $result = $this->nodeRepository->findOneByName($event->nodeName());
            return [
                "ProxmoxUserName"=>$result->getPveUserName()
            ];
        }catch(Error $e){
            $this->logger->error("Error in GetInformationNOde ->". $e->getMessage());
        }
        return null;
    }

}