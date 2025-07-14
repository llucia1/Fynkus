<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Queries;

use GridCP\Common\Domain\Bus\Query\Query;

final class SearchNodeByNameQuerie implements Query
{
    public function __construct(private ?string $name){

    }

    public function name():?string{
        return $this->name;
    }

}