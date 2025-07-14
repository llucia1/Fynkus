<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\Service;

use GridCP\Node\Domain\VO\Node;

interface iCreateNodeService
{
    function create(Node $node):string;
}