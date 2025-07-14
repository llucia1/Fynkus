<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\VO;

use Error;
use GridCP\Common\Domain\Aggregate\AggregateRoot;
use GridCP\Common\Domain\ValueObjects\UuidValueObject;
use GridCP\Node\Domain\EventSource\Event\NodeCreatedDomainEvent;
use GridCP\Node\Domain\Exception\CreateNodeEventError;

final class Node extends AggregateRoot
{
    public function __construct( // NOSONAR
        private readonly ?NodeUuid        $uuid,
        private readonly ?NodeGCPName     $nodeGCPName,
        private readonly ?NodeVPEName     $nodeVPEName,
        private readonly ?NodeVPEHostName $nodeVPEHostName,
        private readonly ?NodeVPEUsername $nodeVPEUsername,
        private readonly ?NodeVPEPassword $nodeVPEPassword,
        private readonly ?NodeVPERealm    $nodeVPERealm,
        private readonly ?NodeVPEPort     $nodeVPEPort,
        private readonly ?NodeVPEIp       $nodeVPEIp,
        private readonly ?NodeSshPort     $sshPort,
        private readonly ?NodeTimeZone    $timeZone,
        private readonly ?NodeKeyboard    $keyboard,
        private readonly ?NodeDisplay          $display,
        private readonly ?NodeStorage          $storage,
        private readonly ?NodeStorageIso       $storageIso,
        private readonly ?NodeStorageImage     $storageImage,
        private readonly ?NodeStorageBackup    $storageBackup,
        private readonly ?NodeNetworkInterface $networkInterface,
        private readonly ?Cpu $cpu,
        private readonly ?Noderiority $priority = null,
        private readonly ?FloatgroupsUuids $floatgroupsUuids = null,
        private readonly ?NodeOsName $osName = null
    ) {}

    public static function create(// NOSONAR
                                    ?NodeUuid         $uuid, 
                                    ?NodeGCPName $nodeGCPName, 
                                    ?NodeVPEName $nodeVPEName, 
                                    ?NodeVPEHostName $nodeVPEHostName, 
                                    ?NodeVPEUsername $nodeVPEUsername,
                                    ?NodeVPEPassword  $nodeVPEPassword, 
                                    ?NodeVPERealm $nodeVPERealm, 
                                    ?NodeVPEPort $nodeVPEPort, 
                                    ?NodeVPEIp $nodeVPEIp, 
                                    ?NodeSshPort $sshPort,
                                    ?NodeTimeZone     $timeZone=null,
                                    ?NodeKeyboard $keyboard=null,
                                    ?NodeDisplay $display=null,
                                    ?NodeStorage $storage=null,
                                    ?NodeStorageIso $storageIso=null,
                                    ?NodeStorageImage $storageImage=null,
                                    ?NodeStorageBackup $storageBackup=null,
                                    ?NodeNetworkInterface $networkInterface=null,
                                    ?Cpu $cpu = null,
                                    ?Noderiority $priority = null,
                                    ?FloatgroupsUuids $floatgroupsUuids = null,
                                    ?NodeOsName $osName = null
                                    ): self
    {
        try {
            $node = new self($uuid, $nodeGCPName, $nodeVPEName, $nodeVPEHostName,$nodeVPEUsername, $nodeVPEPassword, $nodeVPERealm, $nodeVPEPort,
                $nodeVPEIp, $sshPort, $timeZone, $keyboard, $display, $storage, $storageIso, $storageImage, $storageBackup, $networkInterface, $cpu, $priority, $floatgroupsUuids, $osName );
            //$node->record(new NodeCreatedDomainEvent(UuidValueObject::random()->value(), $uuid->value(), $name->value(), $hostName->value(), $ip->value(), $sshPort->value(), $timeZone->value(), $keyboard->value(), $display->value(), $storage->value(), $storageIso->value(), $storageImage->value(), $storageBackup->value(), $networkInterface->value()));
            return $node;
        } catch (Error $e) {
            throw new CreateNodeEventError($e);
        }
    }

    public function uuid(): ?NodeUuid
    {
        return $this->uuid;
    }

    public function osName(): ?NodeOsName
    {
        return $this->osName;
    }
    public function node_gcp_name(): ?NodeGCPName
    {
        return $this->nodeGCPName;
    }

    public function node_vpe_name(): ?NodeVPEName
    {
        return $this->nodeVPEName;
    }

    public function vpe_hostName(): ?NodeVPEHostName
    {
        return $this->nodeVPEHostName;
    }

    public function vpe_username(): ?NodeVPEUsername
    {
        return $this->nodeVPEUsername;
    }

    public function vpe_password():?NodeVPEPassword
    {
        return $this->nodeVPEPassword;
    }

    public function vpe_realm():?NodeVPERealm
    {
        return $this->nodeVPERealm;
    }

    public function vpe_port():?NodeVPEPort
    {
        return $this->nodeVPEPort;
    }

    public function vpe_ip(): ?NodeVPEIp
    {
        return $this->nodeVPEIp;
    }

    public function sshPort(): ?NodeSshPort
    {
        return $this->sshPort;
    }

    public function timeZone(): ?NodeTimeZone
    {
        return $this->timeZone;
    }

    public function keyboard(): ?NodeKeyboard
    {
        return $this->keyboard;
    }

    public function display(): ?NodeDisplay
    {
        return $this->display;
    }

    public function storage(): ?NodeStorage
    {
        return $this->storage;
    }

    public function storageIso(): ?NodeStorageIso
    {
        return $this->storageIso;
    }

    public function storageImage(): ?NodeStorageImage// NOSONAR
    {
        return $this->storageImage;
    }

    public function storageBackup(): ?NodeStorageBackup// NOSONAR
    {
        return $this->storageBackup;
    }

    public function networkInterface(): ?NodeNetworkInterface// NOSONAR
    {
        return $this->networkInterface;
    }

    public function cpu(): ?Cpu// NOSONAR
    {
        return $this->cpu;
    }

    public function priority(): ?Noderiority// NOSONAR
    {
        return $this->priority;
    }

    public function floatgroupsUuid(): ?FloatgroupsUuids// NOSONAR
    {
        return $this->floatgroupsUuids;
    }
}