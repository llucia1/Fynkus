<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;



final class CpuResponse
{
    public function __construct(
        private ?string $vendor,
        private ?string $name,
        private ?int $custom
    ) {
    }

    public function gets(): array
    {
        return [
            'vendor' => $this->vendor(),
            'name' => $this->name(),
            'custom' => $this->custom()
        ];
    }

    public function vendor(): string
    {
        return $this->vendor;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function custom(): int
    {
        return $this->custom;
    }

    public function setVendor(?string $vendor): void
    {
         $this->vendor =$vendor;
    }

    public function setName(?string $name): void
    {
         $this->name =$name;
    }

    public function setCustom(?int $custom): void
    {
         $this->custom =$custom;
    }
}