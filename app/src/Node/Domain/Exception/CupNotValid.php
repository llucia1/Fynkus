<?php
namespace GridCP\Node\Domain\Exception;

use Symfony\Component\HttpFoundation\Response;

use Exception;

class CupNotValid extends Exception
{
    public function __construct( )
    {
        parent::__construct('Cpu content not valid.', Response::HTTP_BAD_REQUEST);
    }

}