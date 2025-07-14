<?php

namespace GridCP\Node\Domain\Service;

use GridCP\Node\Application\Response\NodeResponse;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;

interface IListNodeByUuidService
{
    function getNode(string $uuid): NodeResponse;
    function toResponse(NodeEntity $nodeEntity): NodeResponse;

}