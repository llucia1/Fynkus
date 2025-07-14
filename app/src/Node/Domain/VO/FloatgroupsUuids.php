<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\VO;

class FloatgroupsUuids
{
    private array $floatgroups = [];
    public function __construct(array $all)
    {
        foreach ($all as $floatgroup) {
            $this->floatgroups[] = new FloatgroupUuid($floatgroup);
        }
    }

    public function get(): array
    {
        return $this->floatgroups;
    }

}