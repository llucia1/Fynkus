<?php
declare(strict_types=1);

namespace GridCP\Node\Presentation\Rest\V1;

use Error;
use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Application\Service\ListAllNodesService;
use GridCP\Node\Domain\Exception\GetNodesException;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeException;
use OpenApi\Attributes\Get;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Lambdish\Phunctional\map;
use OpenApi\Attributes\Schema;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Response as OAResponse;

#[Route('/api', name: 'api_v1_')]
final class GetListAllNodes extends AbstractController
{
    public function __construct(private readonly ListAllNodesService $listAllNodes, private readonly LoggerInterface $logger)
    {
    }

    #[Get(
        description: "Get all nodes.",
        summary: "Get all nodes",
        security: [["Bearer" => []]],
        tags: ["Node"],
        responses: [
            "200" => new OAResponse(
                response: "200",
                description: "Success",
                content: new MediaType(
                    mediaType: "application/json",
                    schema: new Schema(
                        type: "object",
                            example: [// NOSONAR
                                "uuid" => "a1b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6",// NOSONAR
                                "gcp_node_name" => "node1",// NOSONAR
                                "pve_node_name" => "node1",// NOSONAR
                                "pve_hostname" => "node1",// NOSONAR
                                "pve_username"=>"username",// NOSONAR
                                "pve_realm"=>"pam",// NOSONAR
                                "pve_port"=>8006,// NOSONAR
                                "pve_ip" => "192.168.1.49",// NOSONAR
                                "ssh_port" => 22,// NOSONAR
                                "timezone" => "Europe/Madrid",// NOSONAR
                                "keyboard" => "es",// NOSONAR
                                "display" => "1920x1080",// NOSONAR
                                "storage" => "/dev/sda",// NOSONAR
                                "storage_iso" => "/dev/sdb",// NOSONAR
                                "storage_image" => "/dev/sdc",// NOSONAR
                                "storage_backup" => "/dev/sdd",// NOSONAR
                                "network_interface" => "enp0s3",// NOSONAR
                                "cpu" => [    // NOSONAR
                                            "vendor" => "GenuineIntel",// NOSONAR
                                            "name" => "KnightsMill",// NOSONAR
                                            "custom" => 0,// NOSONAR
                                        ],// NOSONAR
                                "priority" => 8,// NOSONAR,
                                "floatgroups" => [// NOSONAR
                                                    [// NOSONAR
                                                        "uuid" => "4f79ad5e-8922-4fd7-a09b-e1eb5ec5b215",// NOSONAR
                                                        "name" => "Madrid"// NOSONAR
                                                    ],// NOSONAR
                                "Os" => "Debian12"
                                ]
                            ],// NOSONAR
                    ),
                ),
            ),
            "204" => new OAResponse(
                response: "204",
                description: "Not Found Nodes",
            ),
            "500" => new OAResponse(
                response: "500",
                description: "Internal Server Error",
                content: new MediaType(
                    mediaType: "application/json",
                    schema: new Schema(
                        type: "object",
                        example: [
                            "error" => "Error obtain list nodes",
                        ],
                    ),
                ),
            ),
        ],
    )]

    #[Route('/v1/node', name: 'get_all_nodes', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        try {
            $this->logger->info('Get All nodes');
            $nodes = $this->listAllNodes->__invoke();
            return new JsonResponse(
                map(
                    fn(NodeResponse $node) : array => [
                        'uuid' => $node->uuid(),
                        'gcp_node_name' => $node->gcp_name(),
                        'pve_node_name'=> $node->pve_name(),
                        'pve_hostname' => $node->pve_hostname(),
                        'pve_username' => $node->pve_username(),
                        'pve_realm' => $node->pve_realm(),
                        'pve_port' => $node->pve_port(),
                        'pve_ip' => $node->pve_ip(),
                        'ssh_port' => $node->ssh_port(),
                        'timezone' => $node->timezone(),
                        'keyboard' => $node->keyboard(),
                        'display' => $node->display(),
                        'storage' => $node->storage(),
                        'storage_iso' => $node->storage_iso(),
                        'storage_image' => $node->storage_image(),
                        'storage_backup' => $node->storage_backup(),
                        'network_interface' => $node->network_interface(),
                        'cpu' => $node->cpu(),
                        "priority" => $node->priority(),
                        "floatgroups" => array_map( // NOSONAR
                            fn($relation) => (object)[
                                'uuid' => $relation->uuid(),
                                'name' => $relation->name()
                            ],
                            $node->floatgroups()
                        ),
                        "Os" => $node->os()
                    ],
                    $nodes->nodes()
                ),
                Response::HTTP_OK,
                ['Access-Control-Allow-Origin' => '*']
            );
        } catch (\Exception $e) {
            $this->logger->error('Exception:( -> ' . $e->getMessage());
            $vmException = new NodeException();
            return $vmException($e);
        }
    }
}