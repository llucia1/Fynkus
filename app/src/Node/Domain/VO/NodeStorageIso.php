<?php

namespace GridCP\Node\Domain\VO;

use GridCP\Common\Domain\ValueObjects\StringNullValueObject;

class NodeStorageIso extends StringNullValueObject
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 250;

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
                    'The storage iso value is very large: %d.',
                    $length
                )
            );
        }
    }

}