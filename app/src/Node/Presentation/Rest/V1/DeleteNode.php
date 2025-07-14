<?php
declare(strict_types=1);

namespace GridCP\Node\Presentation\Rest\V1;

use Exception;
use GridCP\Node\Application\Service\DeleteNodeService;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeException;
use GridCP\Node\Domain\VO\NodeUuid;
use InvalidArgumentException;
use OpenApi\Attributes\Delete;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Parameter;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_v1_')]
class DeleteNode extends AbstractController
{
    public function __construct(private readonly DeleteNodeService $deleteNodeService, private readonly LoggerInterface $logger)
    {
    }

    #[Delete(
        description: "Delete a Node with the given UUID.",
        summary: "Delete a Node",
        security: [["Bearer" => []]],
        tags: ["Node"],
        responses: [
            "200" => new OAResponse(
                response: "200",
                description: "Deleted",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["status" => "a5b9c9c0-5b1e-4e1a-8b1a-0e2e8c0f8c0e has been deleted"],// NOSONAR
                    ),
                ),
            ),
            "204" => new OAResponse(
                response: "204",
                description: "Not Found Nodes",
            ),
            "404" => new OAResponse(
                response: "404",
                description: "Node not found",// NOSONAR
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: [""],
                    ),
                ),
            ),
            "500" => new OAResponse(
                response: "500",
                description: "Internal Server Error",// NOSONAR
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Internal Server Error"],// NOSONAR
                    ),
                ),
            ),
        ],
    )]

    #[Route('/v1/node/{uuid}', name: 'delete_node', methods: ['DELETE'])]
    public function __invoke(string $uuid): JsonResponse
    {
        try {
            $this->logger->info("Deleting node with UUID $uuid");
            $nodeUuid = new NodeUuid($uuid);
            $this->deleteNodeService->__invoke($nodeUuid->value());
            return new JsonResponse(null, status: Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            $this->logger->error('Exception:( -> ' . $e->getMessage());
            $vmException = new NodeException();
            return $vmException($e);
        }
        return $result;
    }
}