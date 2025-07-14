<?php

namespace GridCP\Node\Domain\VO;

use GridCP\Common\Domain\ValueObjects\StringValueObject;
use GridCP\Node\Domain\Exception\CupNotValid;

class CpuVendor extends StringValueObject
{
    public function validate():void
    {
        $value = $this->value();
        if ($value !== null && $value !== '' && !preg_match('/^[a-zA-Z0-9+\-_\/]{1,100}$/', $value)) {
            throw new CupNotValid();
        }
    }
}