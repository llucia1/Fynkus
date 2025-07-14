<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Queries;

use GridCP\Common\Domain\Bus\Query\Query;

final class SearchNodeEntityByUuidQuerie implements Query
{
    public function __construct(private ?string $uuid){

    }

    public function uuid():?string{
        return $this->uuid;
    }

}