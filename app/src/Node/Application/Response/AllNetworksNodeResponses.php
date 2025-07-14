<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;
use GridCP\Common\Domain\Bus\Query\Response;

class AllNetworksNodeResponses implements Response
{
    private  $networks;

    public function __construct($networks)
    {
        $this->networks = $networks;
    }

    public function get()
    {
        return $this->networks;
    }
}