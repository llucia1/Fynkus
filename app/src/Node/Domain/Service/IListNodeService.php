<?php

namespace GridCP\Node\Domain\Service;

use GridCP\Node\Application\Response\NodeResponses;

interface IListNodeService
{
    function getAll():NodeResponses;
    function toResponse(array $nodes):NodeResponses;

}