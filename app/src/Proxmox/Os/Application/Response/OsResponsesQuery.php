<?php
declare(strict_types=1);

namespace GridCP\Proxmox\Os\Application\Response;

use GridCP\Common\Domain\Bus\Query\Response;

final class OsResponsesQuery implements Response
{
    private $oss;
    public function __construct($os)
    {
        $this->oss = $os;
    }

    public function get()
    {
        return $this->oss;
    }
}