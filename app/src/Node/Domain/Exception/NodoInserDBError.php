<?php
namespace GridCP\Node\Domain\Exception;


use Exception;

class NodoInserDBError extends Exception
{
    public function __construct(string $msnError)
    {
        parent::__construct('Error insert nodo in DB.'.$msnError, 409);
    }

}