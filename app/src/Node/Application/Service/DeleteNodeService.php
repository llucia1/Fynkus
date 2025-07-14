<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Service;

use Exception;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Repository\INodeRepository;
use GridCP\Node\Domain\Service\IDeleteNodeService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Psr\Log\LoggerInterface;

readonly class DeleteNodeService implements IDeleteNodeService
{
    public function __construct(
        private INodeRepository $nodeRepository, private readonly LoggerInterface $logger
    )
    {
    }

    public function __invoke(string $uuid): void
    {
        $this->delete($uuid);
    }

    public function delete(string $uuid): void
    {
        $this->logger->info('Start Delete a Node');
        $node = $this->nodeRepository->findByUuid($uuid);
        if (is_null($node)) {
            throw new ListNodesEmptyException();
        };
        try {
            $this->nodeRepository->delete($node);
        } catch (Exception $e) {
            return new JsonResponse(['Error in delete Node ' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}