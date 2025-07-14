<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;
use GridCP\Common\Domain\Bus\Query\Response;
use GridCP\Node\Infrastructure\DB\MySQL\Entity\NodeEntity;

final readonly class NodeEntityResponse implements Response
{
    private NodeEntity $node;

    public function __construct(?NodeEntity $node)
    {
        $this->node = $node;
    }

    public function get(): ?NodeEntity
    {
        return $this->node;
    }
}