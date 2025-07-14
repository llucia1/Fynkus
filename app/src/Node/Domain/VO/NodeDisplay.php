<?php

namespace GridCP\Node\Domain\VO;

use GridCP\Common\Domain\ValueObjects\StringNullValueObject;

class NodeDisplay extends StringNullValueObject
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 20;

    public function __construct(protected ?string $value)
    {
        $this->ensureValidLength($value);
    }

    private function ensureValidLength(?string $value): void
    {
        if ($value === null) {
            return;
        }
        $length = strlen($value);
        
        if ($length < self::MIN_LENGTH || $length > self::MAX_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The display value must be a maximum %d characters long, got %d.',
                    self::MIN_LENGTH,
                    self::MAX_LENGTH,
                    $length
                )
            );
        }
    }
}