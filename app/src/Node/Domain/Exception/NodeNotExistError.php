<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\Exception;


use Exception;
use GridCP\Common\Domain\Bus\Query\Response;

final class NodeNotExistError extends Exception  implements Response
{
    public function __construct()
    {
        parent::__construct(sprintf("Node not Exits"));
    }
}