<?php
declare(strict_types=1);

namespace GridCP\Node\Presentation\Rest\V1;

use Exception;
use FOS\RestBundle\Controller\Annotations\Route;
use GridCP\Node\Application\Service\ListNodeByUuidservice;
use GridCP\Node\Domain\Exception\ListNodesEmptyException;
use GridCP\Node\Domain\Exception\NodeException;
use GridCP\Node\Domain\VO\NodeUuid;
use InvalidArgumentException;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


#[Route('/api', name: 'api_v1_')]
final class GetNodeByUuid extends AbstractController
{
    public function __construct(private readonly ListNodeByUuidService $listNodeByUuidService, private readonly LoggerInterface $logger)
    {
    }

    #[Get(
        description: "Get node by uuid.",
        summary: "Get node by uuid",
        security: [["Bearer" => []]],
        tags: ["Node"],
        responses: [
            "200" => new OAResponse(
                response: "200",
                description: "Success",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: [
                            "uuid" => "a1b2c3d4-e5f6-g7h8-i9j0-k1l2m3n4o5p6",
                            "gcp_node_name" => "node1",
                            "pve_node_name" => "node1",
                            "pve_hostname" => "node1",
                            "pve_username"=>"username",
                            "pve_realm"=>"pam",
                            "pve_port"=>8006,
                            "pve_ip" => "192.168.1.49",
                            "ssh_port" => 22,
                            "timezone" => "Europe/Madrid",
                            "keyboard" => "es",
                            "display" => "1920x1080",
                            "storage" => "/dev/sda",
                            "storage_iso" => "/dev/sdb",
                            "storage_image" => "/dev/sdc",
                            "storage_backup" => "/dev/sdd",
                            "network_interface" => "enp0s3",
                            "cpu" => [
                                        "vendor" => "GenuineIntel",
                                        "name" => "KnightsMill",
                                        "custom" => 0,
                            ],
                            "priority" => 8,
                            "floatgroups" => [
                                                [
                                                    "uuid" => "4f79ad5e-8922-4fd7-a09b-e1eb5ec5b215",
                                                    "name" => "Ubrique"
                                                ],
                                                [
                                                    "uuid" => "2008ad5e-8922-4fd7-a09b-e1eb5ec5bb08",
                                                    "name" => "Madrid"
                                                ]
                            ],// NOSONAR
                            "Os" => "Debian12"
                        ],
                    ),
                ),
            ),
            "204" => new OAResponse(
                response: "204",
                description: "Not Found Nodes",
            ),
            "400" => new OAResponse(
                response: "400",
                description: "Bad Request",
            ),
            "404" => new OAResponse(
                response: "404",
                description: "Not Found",
            ),
            "500" => new OAResponse(
                response: "500",
                description: "Internal Server Error",
            ),
        ],
    )]

    #[Route('/v1/node/{uuid}', name: 'get_node_by_uuid', methods: ['GET'])]
    public function __invoke(string $uuid) :JsonResponse
    {
        try {
            $this->logger->info('Get Node By Uuid');
            $nodeUuid = new NodeUuid($uuid);

            $node = $this->listNodeByUuidService->__invoke($nodeUuid->value());
            $result = new JsonResponse(
                [
                    'uuid' => $node->uuid(),
                    'gcp_node_name' => $node->gcp_name(),
                    'pve_node_name' => $node->pve_name(),
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
                    'priority' => $node->priority(),
                    "floatgroups" => array_map(
                        fn($relation) => (object)[
                            'uuid' => $relation->uuid(),
                            'name' => $relation->name()

                        ],
                        $node->floatgroups()
                    ),
                    "Os" => $node->os()
                ],
                Response::HTTP_OK,
                ['Access-Control-Allow-Origin' => '*']
            );
        } catch (\Exception $e) {
            $this->logger->error('Exception:( -> ' . $e->getMessage());
            $vmException = new NodeException();
            return $vmException($e);
        }
        return $result;
    }

}