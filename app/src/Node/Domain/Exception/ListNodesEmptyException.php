<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\Exception;
use Exception;
use Symfony\Component\HttpFoundation\Response;

use Error;

class ListNodesEmptyException extends Exception
{
    public function __construct()
    {
        parent::__construct(sprintf('Not Found Nodes'));
    }
}