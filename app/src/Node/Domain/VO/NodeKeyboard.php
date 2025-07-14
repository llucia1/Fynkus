<?php

namespace GridCP\Node\Domain\VO;

use GridCP\Common\Domain\ValueObjects\StringNullValueObject;

class NodeKeyboard extends StringNullValueObject
{
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 5;

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
                    'The keyboard value must be a maximum %d characters long, got %d.',
                    self::MIN_LENGTH,
                    self::MAX_LENGTH,
                    $length
                )
            );
        }
    }
}