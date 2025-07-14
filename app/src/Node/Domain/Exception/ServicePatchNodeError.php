<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\Exception;
use \Error;
class ServicePatchNodeError extends Error
{
    public  function __construct()
    {
        parent::__construct(sprintf("Error in service patchNode"), 422);
    }
}
