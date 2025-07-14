<?php
namespace GridCP\Node\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

use Error;

class NodoDuplicated extends Error
{
    public function __construct( string $msnError)
    {
        parent::__construct('Node already exists. Duplicate -> ' . $msnError, Response::HTTP_CONFLICT);
    }

}