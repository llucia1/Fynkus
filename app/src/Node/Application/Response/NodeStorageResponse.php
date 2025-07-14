<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;

use GridCP\Common\Domain\Bus\Query\Response;

final readonly class NodeStorageResponse implements Response
{
    public function __construct(
        private string $type,
        private int $used,
        private int $avail,
        private int $total,
        private bool $isEnabled,
        private string $storage,
        private float $usedFraction,
        private string $content,
        private bool $isActived,
        private bool $isShared
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUsed(): int
    {
        return $this->used;
    }

    public function getAvail(): int
    {
        return $this->avail;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function getStorage(): string
    {
        return $this->storage;
    }

    public function getUsedFraction(): float
    {
        return $this->usedFraction;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getIsActived(): bool
    {
        return $this->isActived;
    }

    public function getIsShared(): bool
    {
        return $this->isShared;
    }
}