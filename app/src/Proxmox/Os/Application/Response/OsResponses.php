<?php
declare(strict_types=1);

namespace GridCP\Proxmox\Os\Application\Response;

final class OsResponses
{
    private readonly  array $oss;
    public function __construct(OsResponse ...$os)
    {
        $this->oss = $os;
    }

    public function gets(): array
    {
        return $this->oss;
    }
}