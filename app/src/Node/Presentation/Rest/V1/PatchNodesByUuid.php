<?php
declare(strict_types=1);

namespace GridCP\Node\Presentation\Rest\V1;

use GridCP\Common\Domain\Const\Node\Constants;
use GridCP\Node\Application\Request\PatchNodeRequest;
use GridCP\Node\Application\Service\PatchNodeService;
use GridCP\Node\Domain\VO\Cpu;
use GridCP\Node\Domain\VO\CpuCustom;
use GridCP\Node\Domain\VO\CpuName;
use GridCP\Node\Domain\VO\CpuVendor;
use GridCP\Node\Domain\VO\NodeDisplay;
use GridCP\Node\Domain\VO\NodeUuid;
use GridCP\Node\Domain\VO\NodeVPEIp;
use GridCP\Node\Domain\VO\NodeKeyboard;
use GridCP\Node\Domain\VO\NodeGCPName;
use GridCP\Node\Domain\VO\NodeNetworkInterface;
use GridCP\Node\Domain\VO\NodeVPEHostName;
use GridCP\Node\Domain\VO\NodeVPEName;
use GridCP\Node\Domain\VO\NodeVPEPassword;
use GridCP\Node\Domain\VO\NodeVPEPort;
use GridCP\Node\Domain\VO\NodeVPERealm;
use GridCP\Node\Domain\VO\NodeVPEUsername;
use GridCP\Node\Domain\VO\NodeSshPort;
use GridCP\Node\Domain\VO\NodeStorage;
use GridCP\Node\Domain\VO\NodeStorageBackUp;
use GridCP\Node\Domain\VO\NodeStorageImage;
use GridCP\Node\Domain\VO\NodeStorageIso;
use GridCP\Node\Domain\VO\NodeTimeZone;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Patch;
use OpenApi\Attributes\RequestBody;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use GridCP\Common\Infrastructure\OpenSSL\OpenSSLService;
use GridCP\Node\Domain\Exception\NodeException;
use GridCP\Node\Domain\VO\FloatgroupsUuids;
use GridCP\Node\Domain\VO\Node;
use GridCP\Node\Domain\VO\NodeOsName;
use GridCP\Node\Domain\VO\Noderiority;
use InvalidArgumentException;

#[Route('/api', name: 'api_v1_')]
final class PatchNodesByUuid extends AbstractController
{
    public function __construct(
        private readonly PatchNodeService $pathNodeService, private readonly LoggerInterface  $logger, public readonly  OpenSSLService $openSSLService,
    ){}

    #[Patch(
        description: "Update Node by id with the provided data.",
        summary: "Update Node",
        tags: ["Node"],
        responses: [
            "204" => new OAResponse(
                response: "204",
                description: "Update",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: [""],
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
                        example: ["error" => "Bad Request"],
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
                        example: ["error" => "Unauthorized"],
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
                        example: ["error" => "Forbidden"],
                    ),
                ),
            ),
            "404" => new OAResponse(
                response: "404",
                description: "Not Found Node",
                content: new MediaType(
                    mediaType: "application/json",// NOSONAR
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Not Found"],
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
                        example: ["error" => "Node already exists. Duplicate"],
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
                        example: ["error" => "Internal Server Error"],
                    ),
                ),
            ),
        ],
    )]


    #[RequestBody(
        description: "Provide the Node data to update a Node.",
        required: true,
        content: new MediaType(
            mediaType: "application/json",// NOSONAR
            schema: new Schema(
                ref: new Model(type: PatchNodeRequest::class )
            )
        ),
    )]

    #[Route('/v1/node/{nodeUuid}', name: 'path_node_by_id', methods: ['PATCH'])]
    public function __invoke(PatchNodeRequest $request, string $nodeUuid): JsonResponse
    {
        try {

                $this->logger->info('Start update node by id' . $request->gcp_node_name());

                $this->pathNodeService->__invoke($this->setVo($request, $nodeUuid));
                $result = new JsonResponse(null, status: Response::HTTP_NO_CONTENT);
            } catch (\Exception $e) {
                $this->logger->error('Exception:( -> ' . $e->getMessage());
                $vmException = new NodeException();
                return $vmException($e);
            }
            return $result;
        }
        private function setVo( PatchNodeRequest $request, string $nodeUuid ): Node// NOSONAR
        {
            $nodeUuid = ($nodeUuid !== null)? new NodeUuid($nodeUuid) : null;
            $nodeGCPName = ($request->gcp_node_name() !== null)? new NodeGCPName($request->gcp_node_name()) : null;
            $nodeVPEName = ($request->pve_node_name() !== null)? new NodeVPEName($request->pve_node_name()): null;
            $nodeVPEIp = ($request->pve_ip() !== null)? new NodeVPEIp($request->pve_ip()) : null;
            $pveHostname = ($request->pve_hostname()  !== null )? new NodeVPEHostName($request->pve_hostname()) : null;
            $pveUsername = ($request->pve_username() !== null)? new NodeVPEUsername($request->pve_username()) : null;


            $pvePassword = (!is_null($request->pve_password()))? new NodeVPEPassword($this->openSSLService->encrypt($request->pve_password())) : null;

            $pveRealm = ($request->pve_realm() !== null)? new NodeVPERealm($request->pve_realm()) : null;
            $pvePort = ($request->pve_port() !== null)? new NodeVPEPort($request->pve_port()) : null;

            $nodeSshPort = ($request->sshPort() !== null)? new NodeSshPort($request->sshPort()) : null;
            $nodeTimeZone = ($request->timeZone() !== null)? new NodeTimeZone($request->timeZone() == Constants::NOT_SET ? null : $request->timeZone() ) : null;
            $nodeKeyboard = ($request->keyboard() !== null)? new NodeKeyboard($request->keyboard() == Constants::NOT_SET ? null : $request->keyboard()) : null;
            $nodeDisplay = ($request->display() !== null)? new NodeDisplay($request->display() == Constants::NOT_SET ? null : $request->display() ) : null;
            $nodeStorage = ($request->storage() !== null)? new NodeStorage($request->storage()  == Constants::NOT_SET ? null : $request->storage()) : null;
            $nodeStorageIso = ($request->storageIso() !== null)? new NodeStorageIso($request->storageIso() == Constants::NOT_SET ? null : $request->storageIso()) : null;
            $nodeStorageImage = ($request->storageImage() !== null)? new NodeStorageImage($request->storageImage() == Constants::NOT_SET ? null : $request->storageImage()) : null;
            $nodeStorageBackup = ($request->storageBackup() !== null)? new NodeStorageBackup($request->storageBackup() == Constants::NOT_SET ? null : $request->storageBackup()) : null;
            $nodeNetworkInterface = ($request->networkInterface() !== null)? new NodeNetworkInterface($request->networkInterface()) : null;
            $priority = ($request->priority() !== null)? new Noderiority($request->priority()) : null;
            $floatgroupsUuids = ($request->floatgroups() !== null)? new FloatgroupsUuids($request->floatgroups()) : null;
            $nodeOsName = $request->os() ? new NodeOsName($request->os()) : null;

            $cpu = $this->setCpu($request);

            return new Node($nodeUuid,$nodeGCPName,$nodeVPEName, $pveHostname,$pveUsername,$pvePassword,$pveRealm,$pvePort,$nodeVPEIp,$nodeSshPort,$nodeTimeZone,$nodeKeyboard,$nodeDisplay,$nodeStorage,$nodeStorageIso, $nodeStorageImage,$nodeStorageBackup,$nodeNetworkInterface,$cpu,$priority,$floatgroupsUuids,$nodeOsName );
 
        }

     private function setCpu( PatchNodeRequest $request):?Cpu

        {
            $cpu = null;
            if (!is_null($request->cpu())) {
                if ($request->cpu() == Constants::NOT_SET)
                {
                    $cpu = Cpu::notSet();
                } else {
                    (isset($request->cpu()['name']))?  $cpuName = new CpuName($request->cpu()['name']) : $cpuName = null;
                    (isset($request->cpu()['vendor']))? $cpuVendor = new CpuVendor($request->cpu()['vendor']) : $cpuVendor = null;
                    (isset($request->cpu()['custom']))? $cpuCustom = new CpuCustom($request->cpu()['custom']) : $cpuCustom = null;
        
                    $cpu = new Cpu($cpuName, $cpuVendor, $cpuCustom);
                }
            }
            return $cpu;
        }


    }