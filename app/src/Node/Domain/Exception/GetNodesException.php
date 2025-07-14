<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\Exception;

use Error;

class GetNodesException extends Error
{
    public function __construct(Error $e)
    {
        parent::__construct(sprintf($e->getMessage(), 'Error obtain list nodes'));
    }
}