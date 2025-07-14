<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\Exception;

use GridCP\Common\Domain\Exceptions\OpenSSLEncryptError;
use GridCP\Proxmox\Vm\Domain\Exception\IpNotFoundException;
use GridCP\Proxmox\Vm\Domain\Exception\ListNodesEmptyError;
use GridCP\Proxmox\Vm\Domain\Exception\OsNameNotExistException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;



final class NodeException {//NOSONAR

    public function __invoke(\exception $e):JsonResponse{
                return $this->handleException($e);
     }


    protected function handleException(\Exception $e): JsonResponse
    {
        $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = 'Unexpected error occurred.';
        
        switch (true) {
            case $e instanceof InvalidArgumentException:
                $status = Response::HTTP_BAD_REQUEST;
                $message = $e->getMessage();
                break;
    
            case $e instanceof ListNodesEmptyException:
                $status = Response::HTTP_NOT_FOUND;
                $message = $e->getMessage();
                break;            



            case $e instanceof IpNotFoundException:
            case $e instanceof ListNodesEmptyError:
            case $e instanceof NodoDuplicatedException:
            case $e instanceof NodoInserDBError:
            case $e instanceof CupNotValid:
            case $e instanceof NodeNotExistError:
            case $e instanceof FreeIpsNotExistError:
            case $e instanceof OsNameNotExistException:
            case $e instanceof OpenSSLEncryptError:
                $status = Response::HTTP_CONFLICT;
                $message = $e->getMessage();
                break;
    
            case $e instanceof HttpException:
                $status = $e->getStatusCode();
                $message = $e->getMessage();
                break;
    
            default:
                break;
        }
    
        return new JsonResponse(['error' => $message], $status);
    }
}
