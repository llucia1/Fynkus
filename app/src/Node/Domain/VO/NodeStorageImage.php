<?php

namespace GridCP\Node\Domain\VO;

use GridCP\Common\Domain\ValueObjects\StringNullValueObject;

class NodeStorageImage extends StringNullValueObject
{
    private const MIN_LENGTH = 1;
    private const MAX_LENGTH = 255;

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
                    'The Storage Image value is very large: %d.',
                    $length
                )
            );
        }
    }

}