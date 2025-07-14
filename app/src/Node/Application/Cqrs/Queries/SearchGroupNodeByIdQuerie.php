<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Cqrs\Queries;

use GridCP\Common\Domain\Bus\Query\Query;

final class SearchGroupNodeByIdQuerie implements Query
{
    public function __construct(private ?array $ids){

    }

    public function gets():?array{
        return $this->ids;
    }

}