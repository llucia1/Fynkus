<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;
use GridCP\Common\Domain\Bus\Query\Response;

final readonly class NodeResponses implements Response
{
    private array $nodes;

    public function __construct(NodeResponse ...$nodes)
    {
        $this->nodes = $nodes;
    }

    public function nodes(): array
    {
        return $this->nodes;
    }
}