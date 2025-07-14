<?php
declare(strict_types=1);

namespace GridCP\Node\Domain\Model;

use Error;
use GridCP\Node\Domain\Exception\CreateNodeEventError;

class NodeModel
{



    public function __construct(
                                    private ?int   $id = null,
                                    public ?string $uuid = null,
                                    public ?string $gpc_node_name = null,
                                    public ?string $pve_node_name = null,
                                    public ?string $pve_hostname = null,
                                    public ?string $pve_username = null,
                                    public ?string $pve_password = null,
                                    public ?string $pve_realm = null,
                                    public ?int    $pve_port = null,
                                    public ?string $pve_ip = null,
                                    public ?int    $ssh_port = null,
                                    public ?string $timezone = null,
                                    public ?string $keyboard = null,
                                    public ?string $display = null,
                                    public ?string $storage = null,
                                    public ?string $storage_iso = null,
                                    public ?string $storage_image = null,
                                    public ?string $storage_backup = null,
                                    public ?string $network_interface = null,
                               )
    {

    }
    public static function create(
                                    ?string $uuid,
                                    ?string $gpc_node_name,
                                    ?string $pve_node_name,
                                    ?string $pve_hostname,
                                    ?string $pve_username,
                                    ?string $pve_password,
                                    ?string $pve_realm,
                                    ?int    $pve_port,
                                    ?string $pve_ip,
                                    ?int    $ssh_port,
                                    ?string $timezone,
                                    ?string $keyboard,
                                    ?string $display,
                                    ?string $storage,
                                    ?string $storage_iso,
                                    ?string $storage_image,
                                    ?string $storage_backup,
                                    ?string $network_interface,
                                    ?array $cpu,
                                    ?int    $id = null,
                                  ): self
    {
        try {
            $node = new self(
                                $id,
                                $uuid,
                                $gpc_node_name,
                                $pve_node_name,
                                $pve_hostname,
                                $pve_username,
                                $pve_password,
                                $pve_realm,
                                $pve_port,
                                $pve_ip,
                                $ssh_port,
                                $timezone,
                                $keyboard,
                                $display,
                                $storage,
                                $storage_iso,
                                $storage_image,
                                $storage_backup,
                                $network_interface,
                                $cpu
            );
            //$node->record(new NodeCreatedDomainEvent(UuidValueObject::random()->value(), $uuid->value(), $name->value(), $hostName->value(), $ip->value(), $sshPort->value(), $timeZone->value(), $keyboard->value(), $display->value(), $storage->value(), $storageIso->value(), $storageImage->value(), $storageBackup->value(), $networkInterface->value()));
            return $node;
        } catch (Error $e) {
            throw new CreateNodeEventError($e);
        }
    }
}