<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\Service;
use GridCP\Node\Domain\VO\Node;

interface IPatchNodeService
{
    function update(Node $node): void;

}