<?php
declare(strict_types=1);

namespace GridCP\Node\Presentation\Rest\V1;

use Exception;
use GridCP\Common\Domain\Exceptions\OpenSSLEncryptError;
use GridCP\Common\Infrastructure\OpenSSL\OpenSSLService;
use GridCP\Node\Application\Request\CreateNodeRequest;
use GridCP\Node\Application\Service\CreateNodeService;
use GridCP\Node\Domain\Exception\NodeException;
use GridCP\Node\Domain\Exception\NodoDuplicated;
use GridCP\Node\Domain\VO\FloatgroupsUuids;
use GridCP\Node\Domain\VO\Node;
use GridCP\Node\Domain\VO\NodeVPEHostName;
use GridCP\Node\Domain\VO\NodeVPEIp;
use GridCP\Node\Domain\VO\NodeGCPName;
use GridCP\Node\Domain\VO\NodeOsName;
use GridCP\Node\Domain\VO\Noderiority;
use GridCP\Node\Domain\VO\NodeVPEName;
use GridCP\Node\Domain\VO\NodeVPEPassword;
use GridCP\Node\Domain\VO\NodeVPEPort;
use GridCP\Node\Domain\VO\NodeVPERealm;
use GridCP\Node\Domain\VO\NodeVPEUsername;
use GridCP\Node\Domain\VO\NodeSshPort;
use GridCP\Node\Domain\VO\NodeUuid;
use InvalidArgumentException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_v1_')]
final class PostCreateNode extends AbstractController
{
    public function __construct(
        private readonly CreateNodeService $createNodeService,
        private readonly LoggerInterface  $logger,
        public readonly  OpenSSLService $openSSLService,
    )
    {
    }

    #[Post(
        description: "Register new Node with the provided data.",
        summary: "Register new Node",
        tags: ["Node"],
        responses: [
            "201" => new OAResponse(
                response: "201",
                description: "Created",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["uuid" => "d0b9c9c0-5b1e-4e1a-8b1a-0e2e8c0f8c0e"],
                    ),
                ),
            ),
            "400" => new OAResponse(
                response: "400",
                description: "Bad Request",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Bad Request"],// NOSONAR
                    ),
                ),
            ),
            "401" => new OAResponse(
                response: "401",
                description: "Unauthorized",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Unauthorized"],// NOSONAR
                    ),
                ),
            ),
            "403" => new OAResponse(
                response: "403",
                description: "Forbidden",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Forbidden"],// NOSONAR
                    ),
                ),
            ),
            "404" => new OAResponse(
                response: "404",
                description: "Not Found",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Not Found"],// NOSONAR
                    ),
                ),
            ),
            "409" => new OAResponse(
                response: "409",
                description: "Node already exists. Duplicate",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Node already exists. Duplicate"],// NOSONAR
                    ),
                ),
            ),
            "500" => new OAResponse(
                response: "500",
                description: "Internal Server Error",
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

    #[RequestBody(
        description: "Provide the Node data to create a new Node.",
        required: true,
        content: new MediaType(
            mediaType: "application/json",// NOSONAR
            schema: new Schema(
                ref: new Model(type: CreateNodeRequest::class)
            )
        ),
    )]

    #[Route('/v1/node', name: 'node_create', methods: ['POST'])]
    public function __invoke(CreateNodeRequest $request): JsonResponse
    {
        try {
            $this->logger->info('Start create new node' . $request->gcp_node_name());
            $password_proxmox = $this->openSSLService->encrypt($request->pve_password());
            $nodeUuid = new NodeUuid(NodeUuid::random()->value());
            $nodeGCPName = new NodeGCPName($request->gcp_node_name());
            $nodePVEName = new NodeVPEName($request->pve_node_name());
            $nodeOsName = new NodeOsName($request->os());
            $nodeVPEHostName = new NodeVPEHostName($request->pve_hostName());
            $nodeVPEUsername = new NodeVPEUsername($request->pve_username());
            $nodeVPEPassword = new NodeVPEPassword($password_proxmox);
            $nodeVPERealm = new NodeVPERealm($request->pve_realm());
            $nodeVPEPort = new NodeVPEPort($request->pve_port());
            $nodeVPEIp = new NodeVPEIp($request->pve_ip());
            $nodeSshPort = new NodeSshPort($request->sshPort());
            $priority = new Noderiority($request->priority());
            $floatgroupsUuids = new FloatgroupsUuids($request->floatgroups());

            
            $node = Node::create($nodeUuid, $nodeGCPName,$nodePVEName, $nodeVPEHostName,$nodeVPEUsername, $nodeVPEPassword,
            $nodeVPERealm, $nodeVPEPort,  $nodeVPEIp, $nodeSshPort, null, null, null, null, null, null, null, null, null, $priority, $floatgroupsUuids, $nodeOsName);
            $uuid = $this->createNodeService->__invoke($node);
            $result = new JsonResponse(data: ['uuid' => $uuid], status: Response::HTTP_CREATED);
            
        
        return $result;
        } catch (\Exception $e) {
                $this->logger->error('Exception:( -> ' . $e->getMessage());
                $vmException = new NodeException();
                return $vmException($e);
        }
    }
}