<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\VO;

use Exception;
use Error;
use GridCP\Common\Domain\Aggregate\AggregateRoot;
use GridCP\Common\Domain\Const\Node\Constants;
use GridCP\Node\Domain\Exception\CreateNodeEventError;
use GridCP\Node\Domain\Exception\CupNotValid;

final class Cpu extends AggregateRoot
{
    private ?string   $cpu = null;
    public function __construct(
        private readonly ?CpuName   $name = null,
        private readonly ?CpuVendor $vendor = null,
        private readonly ?CpuCustom $custom = null
    ) {

        try {
            $this->validate();
        } catch (Exception $e) {
            throw new CupNotValid();
        }





    }
    private function validate():void
    {
       if (!is_null($this->name())) $this->name()->validate();
       if (!is_null($this->vendor())) $this->vendor()->validate();
       if (!is_null($this->custom())) $this->custom()->validate();
    }
    

    public static function notSet(): self
    {
        try {
            $cpu = new self(
                                null,
                                null,
                                null
                            );
            $cpu->setNotSet();
            //$node->record(new NodeCreatedDomainEvent(UuidValueObject::random()->value(), $uuid->value(), $name->value(), $hostName->value(), $ip->value(), $sshPort->value(), $timeZone->value(), $keyboard->value(), $display->value(), $storage->value(), $storageIso->value(), $storageImage->value(), $storageBackup->value(), $networkInterface->value()));
            return $cpu;
        } catch (Error $e) {
            throw new CreateNodeEventError($e);
        }
    }

    public static function create(?CpuName $name, ?CpuVendor $vendor, ?CpuCustom $custom): self
    {
        try {
            $cpu = new self(
                                $name,
                                $vendor,
                                $custom
                            );
            //$node->record(new NodeCreatedDomainEvent(UuidValueObject::random()->value(), $uuid->value(), $name->value(), $hostName->value(), $ip->value(), $sshPort->value(), $timeZone->value(), $keyboard->value(), $display->value(), $storage->value(), $storageIso->value(), $storageImage->value(), $storageBackup->value(), $networkInterface->value()));
            return $cpu;
        } catch (Error $e) {
            throw new CreateNodeEventError($e);
        }
    }

    public function value(): array | string | null
    {
        $cpu = $this->getNotSet();
        if ( !$cpu ) {
            $cpu =  Array();
            !is_null($this->name()) ? $cpu['name']= $this->name()->value() : $cpu['name']=null;
            !is_null($this->vendor()) ? $cpu['vendor']= $this->vendor()->value() : $cpu['vendor']=null;
            !is_null($this->custom()) ? $cpu['custom']= $this->custom()->value() : $cpu['custom']=null;
        }
        return $cpu;
    }

    public function setNotSet(): void
    {
        $this->cpu = Constants::NOT_SET;
    }
    public function getNotSet(): ?string
    {
        return $this->cpu;
    }
    

    public function name(): ?CpuName
    {
        return $this->name;
    }

    public function vendor(): ?CpuVendor
    {
        return $this->vendor;
    }

    public function custom(): ?CpuCustom
    {
        return $this->custom;
    }
}