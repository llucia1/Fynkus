<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Response;

use GridCP\Common\Domain\Bus\Query\Response;

final class NodeResponse implements Response
{
    public function __construct(
        private string $uuid,
        private string $gcp_name,
        private string $pve_name,
        private string $pve_hostname,
        private string $pve_username,
        private string $pve_password,
        private string $pve_realm,
        private int    $pve_port,
        private string $pve_ip,
        private int    $ssh_port,
        private ?string $timezone,
        private ?string $keyboard,
        private ?string $display,
        private ?string $storage,
        private ?string $storage_iso,
        private ?string $storage_image,
        private ?string $storage_backup,
        private ?string $network_interface,// bridge
        private ?array $cpu,
        private ?int $id = null,
        private ?int $priority = null,
        private ?array $floatgroups = null,
        private $os = null,

    ) {
    }
    public function id(): ?int
    {
        return $this->id;
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function gcp_name(): string
    {
        return $this->gcp_name;
    }

    public function pve_name():string
    {
        return $this->pve_name;
    }
    public function pve_hostname(): string
    {
        return $this->pve_hostname;
    }

    public function pve_username():string
    {
        return $this->pve_username;
    }

    public function pve_password():string
    {
        return $this->pve_password;
    }
    public function pve_realm():string
    {
        return $this->pve_realm;
    }

    public function pve_port():int
    {
        return $this->pve_port;
    }
    public function pve_ip(): string
    {
        return $this->pve_ip;
    }

    public function ssh_port(): int
    {
        return $this->ssh_port;
    }

    public function timezone(): ?string
    {
        return $this->timezone;
    }

    public function keyboard(): ?string
    {
        return $this->keyboard;
    }

    public function display(): ?string
    {
        return $this->display;
    }

    public function storage(): ?string
    {
        return $this->storage;
    }

    public function storage_iso(): ?string
    {
        return $this->storage_iso;
    }

    public function storage_image(): ?string
    {
        return $this->storage_image;
    }

    public function storage_backup(): ?string
    {
        return $this->storage_backup;
    }

    public function network_interface(): ?string
    {
        return $this->network_interface;
    }
    public function cpu(): ?array
    {
        return is_null($this->cpu)? null : $this->cpu;
    }
    public function priority(): ?int
    {
        return $this->priority;
    }
    public function floatgroups(): ?array
    {
        return $this->floatgroups;
    }
    public function os()
    {
        return $this->os;
    }
}