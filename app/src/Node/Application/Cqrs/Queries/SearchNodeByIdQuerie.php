<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Queries;

use GridCP\Common\Domain\Bus\Query\Query;

final class SearchNodeByIdQuerie implements Query
{
    public function __construct(private ?int $id){

    }

    public function id():?int{
        return $this->id;
    }

}