<?php
declare(strict_types=1);

namespace GridCP\Proxmox\Os\Presentation\Rest\V1;

use GridCP\Proxmox\Os\Application\Response\OsResponse;
use GridCP\Proxmox\Os\Application\Service\GetAllOsService;
use GridCP\Proxmox\Os\Domain\Exception\ListOsEmptyException;
use OpenApi\Attributes\MediaType;
use OpenApi\Attributes\Get;
use OpenApi\Attributes\Response as OAResponse;
use OpenApi\Attributes\Schema;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use function Lambdish\Phunctional\map;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_v1_')]
final class GetAllOs extends AbstractController
{
    public function __construct(
        private readonly GetAllOsService $getAllOsService,
        private readonly LoggerInterface  $logger,
    )
    {
    }

    #[Get(
        description: "Get All OS.",
        summary: "Get All OS.",
        security: [["Bearer" => []]],
        tags: ["Proxmox"],
        responses: [
            "200" => new OAResponse(
                response: "200",
                description: "Success",
                content: new MediaType(
                    mediaType: "application/json",
                    schema: new Schema(
                        type: "object",
                        example: [
                                    [
                                        "uuid" =>  "7a4039e8-3e7e-4f61-a2f5-9bdeab0a2df1",
                                        "name" =>  "Debian12",
                                        "tag" =>  "debian12",
                                        "image" =>  "Debian-12-x86_64-GridCP-PVE_KVM-20241005.qcow2"

                                    ],
                                    [                         
                                        "uuid" =>  "2e9c7f0c-279f-4682-942c-7d0d0b9b48b3",
                                        "name" =>  "Ubuntu24",
                                        "tag" =>  "ubuntu24",
                                        "image" =>  "Ubuntu-24-x86_64-GridCP-PVE_KVM-20240920.img"
                                    ]
                        ],
                    ),
                ),
            ),
            "401" => new OAResponse(
                response: "401",
                description: "Unauthorized",
                content: new MediaType(
                    mediaType: "application/json",
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Unauthorized"],
                    ),
                ),
            ),
            "404" => new OAResponse(
                response: "404",
                description: "Not found",
            ),
            "500" => new OAResponse(
                response: "500",
                description: "Internal Server Error",
                content: new MediaType(
                    mediaType: "application/json",
                    schema: new Schema(
                        type: "object",
                        example: ["error" => "Internal Server Error"],
                    ),
                ),
            ),
        ],
    )]
    #[IsGranted('ROLE_ADMIN')]    
    #[Route('/v1/os', name: 'get_all_os', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        
        try {
            $this->logger->info('Start get All OS.');
            $Sos = $this->getAllOsService->__invoke();
            
            return $this->json(
                map(
                    fn(OsResponse $so): array => [
                        'uuid' => $so->uuid(),
                        'name'=> $so->name(),
                        'tag'=> $so->tag(),
                        "image" => $so->image(),
                    ],
                    $Sos->gets()
                ),
                Response::HTTP_OK
            );
        } catch (ListOsEmptyException $e){
            $this->logger->error('Not Found :( -> ' . $e->getMessage());
            return  new JsonResponse(["error"=>$e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (HttpException $e){
            $this->logger->error('Error in create new OS :( -> ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }
    }
}
