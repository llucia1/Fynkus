<?php
namespace GridCP\Node\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

use Exception;

class NodoDuplicatedException extends Exception
{
    public function __construct( string $msnError)
    {
        parent::__construct('Node already exists. Duplicate -> ' . $msnError, Response::HTTP_CONFLICT);
    }

}