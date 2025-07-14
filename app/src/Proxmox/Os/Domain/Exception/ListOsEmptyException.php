<?php
declare(strict_types=1);

namespace GridCP\Proxmox\Os\Domain\Exception;

use Exception;

class ListOsEmptyException extends Exception
{
    public function __construct()
    {
        parent::__construct('Not Found So.' );
    }
}