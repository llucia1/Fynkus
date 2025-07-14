<?php
declare(strict_types=1);
namespace GridCP\Node\Domain\VO;

use GridCP\Common\Domain\ValueObjects\IntValueObject;
use GridCP\Node\Domain\Exception\CupNotValid;

class CpuCustom extends IntValueObject
{
    public function validate():void
    {
        $value = $this->value();
        if ($value === null || $value === '') {
            throw new CupNotValid();
        }
        
        if (!is_int($value)) {
            throw new CupNotValid();
        }
    }

}