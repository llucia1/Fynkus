<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\VO;

use Exception;
use Error;
use GridCP\Common\Domain\Aggregate\AggregateRoot;
use GridCP\Node\Domain\Exception\CreateNodeEventError;
use GridCP\Node\Domain\Exception\CupNotValid;

final class CpuPath extends AggregateRoot
{
    public function __construct(
        private readonly ?CpuName   $name,
        private readonly ?CpuVendor $vendor,
        private readonly ?CpuCustom $custom
    ) {

        try {
            $this->validate();
        } catch (Exception $e) {
            throw new CupNotValid();
        }





    }
    private function validate():void
    {
        $this->name()->validate();
        $this->vendor()->validate();
        $this->custom()->validate();
    }

    public static function create(?CpuName $name, ?CpuVendor $vendor, ?CpuCustom $custom): self
    {
        try {
            $node = new self(
                                $name,
                                $vendor,
                                $custom
                            );
            //$node->record(new NodeCreatedDomainEvent(UuidValueObject::random()->value(), $uuid->value(), $name->value(), $hostName->value(), $ip->value(), $sshPort->value(), $timeZone->value(), $keyboard->value(), $display->value(), $storage->value(), $storageIso->value(), $storageImage->value(), $storageBackup->value(), $networkInterface->value()));
            return $node;
        } catch (Error $e) {
            throw new CreateNodeEventError($e);
        }
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