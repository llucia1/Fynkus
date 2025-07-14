<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;



final class Floatgroupesponse
{
    public function __construct(
        private ?string $uuid,
        private ?string $name
    ) {
    }

    public function gets(): array
    {
        return [
            'uuid' => $this->uuid(),
            'name' => $this->name()
        ];
    }

    public function uuid(): ?string
    {
        return $this->uuid;
    }

    public function name(): ?string
    {
        return $this->name;
    }
}