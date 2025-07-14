<?php
declare(strict_types=1);
namespace GridCP\Proxmox\Os\Application\Cqrs\Queries;

use GridCP\Common\Domain\Bus\Query\Query;

final readonly class getOsEntityByNameQueried implements Query
{
    public function __construct(private ?string $name){

    }

    public function name():?string{
        return $this->name;
    }
}