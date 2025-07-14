<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\Service;

use Symfony\Component\HttpFoundation\JsonResponse;

interface IDeleteNodeService
{
    function delete(string $uuid): void;

}