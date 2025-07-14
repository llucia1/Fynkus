<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;

final class NodesStorageResponse
{
    private readonly array $storages;

    public function __construct(NodeStorageResponse ...$storages)
    {
        $this->storages = $storages;
    }

    public function storages(): array
    {
        return $this->storages;
    }

}