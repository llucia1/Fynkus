<?php

namespace GridCP\Node\Domain\Exception;

use Error;

final class CreateNodeEventError extends Error
{
    public function __construct(Error $e)
    {
        parent::__construct(sprintf('Error in create nodeDomainEvent', $e->getMessage()));
    }
}