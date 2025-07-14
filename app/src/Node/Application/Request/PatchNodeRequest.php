<?php
declare(strict_types=1);
namespace GridCP\Node\Application\Request;


use GridCP\Common\Application\BaseRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;

use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[Schema(
    schema: "PatchNodeRequest",
    title: "Pacth Node Request",
    description: "Request schema for update a node.",
    required: ['gcp_node_name'],
    type: "object",
)]
class PatchNodeRequest extends BaseRequest
{
    #[Property(
        property: "gcp_node_name",
        description: "The name of the node GPC.",
        type: "string",
        example: "node1"
    )]
    protected ?string $gcp_node_name=null;


    #[Property(
        property: "pve_node_name",
        description: "The name of the node PVE.",
        type: "string",
        example: "node1"
    )]
    protected ?string $pve_node_name=null;

    #[Property(
        property: "pve_hostname",
        description: "The proxmox hostname of the node.",
        type: "string",
        example: "node1"
    )]
    protected ?string $pve_hostname=null;

    #[Property(
        property: "pve_username",
        description: "The proxmox username from node.",
        type: "string",
        example: "root"
    )]
    protected ?string $pve_username=null;

    #[Property(
        property: "pve_password",
        description: "The proxmox password from node.",
        type: "string",
        example: "root"
    )]
    protected ?string $pve_password=null;

    #[Property(
        property: "pve_realm",
        description: "The proxmox realm from node.",
        type: "string",
        example: "pam"
    )]
    protected ?string $pve_realm=null;

    #[Property(
        property: "pve_port",
        description: "The proxmox port from node.",
        type: "integer",
        example: "8006"
    )]
    #[Assert\Type(type: 'integer', message: 'The PROXMOX port must be an integer.')]
    #[Assert\Range(
        notInRangeMessage: 'The PROXMOX port must be in the range of 1 to 65535.',
        min: 1,
        max: 65535
    )]
    protected ?int $pve_port=null;

    #[Property(
        property: "pve_ip",
        description: "The IP address of the node in IPv4 format.",
        type: "string",
        format: "ipv4",
        example: "192.168.1.2"// NOSONAR
    )]
    #[Ip(version:"4", message:"Invalid IP format")]
    protected ?string $pve_ip=null;

    #[Property(
        property: "ssh_port",
        description: "The SSH port of the node.",
        type: "integer",
        example: 22
    )]
    #[Assert\Type(type: 'integer', message: 'The SSH port must be an integer.')]
    #[Assert\Range(
        notInRangeMessage: 'The SSH port must be in the range of 1 to 65535.',
        min: 1,
        max: 65535
    )]
    protected ?int $ssh_port=null;

    #[Property(
        property: "timezone",
        description: "The timezone of the node.",
        type: "string",
        example: "Europe/Moscow"
    )]
    protected ?string $timezone=null;

    #[Property(
        property: "keyboard",
        description: "The keyboard layout of the node.",
        type: "string",
        example: "us"
    )]
    protected ?string $keyboard=null;

    #[Property(
        property: "display",
        description: "The display of the node.",
        type: "string",
        example: "vnc"
    )]
    protected ?string $display=null;

    #[Property(
        property: "storage",
        description: "The storage of the node.",
        type: "string",
        example: "local"
    )]
    protected ?string $storage=null;

    #[Property(
        property: "storage_iso",
        description: "The storage ISO of the node.",
        type: "string",
        example: "local"
    )]
    protected ?string $storage_iso=null;

    #[Property(
        property: "storage_image",
        description: "The storage image of the node.",
        type: "string",
        example: "local"
    )]
    protected ?string $storage_image=null;

    #[Property(
        property: "storage_backup",
        description: "The storage backup of the node.",
        type: "string",
        example: "local"
    )]
    protected ?string $storage_backup=null;

    #[Property(
        property: "network_interface",
        description: "The network interface of the node.",
        type: "string",
        example: "eth0"
    )]
    protected ?string $network_interface=null;

    #[Property(
        property: "cpu",
        description: "CPU information for the node.",
        type: "object",
        example: '{"name": "Cascadelake-Server-v4", "vendor": "GenuineIntel", "custom": 0}'
    )]
    public array | string | null $cpu = null;

    #[Property(
        property: "priority",
        description: "Priority value for the IP address (0-10).",
        example: 8,
        type: "integer",
        minimum: 0,
        maximum: 10
    )]
    #[Assert\Range(min: 0, max: 10, notInRangeMessage: "Priority must be between {{ min }} and {{ max }}.")]
    protected int $priority = 0;

    #[Property(
        property: "floatgroups",
        description: "Floatgroups to add to a node.",
        type: "array",
        items: new OA\Items(
            type: "string",
            example: "d0b9c9c0-5b1e-4e1a-8b1a-0e2e8c0f8c0e"
        ),
        example: ["d0b9c9c0-5b1e-4e1a-8b1a-0e2e8c0f8c0e", "08b9c9c0-5b1e-4e1a-8b1a-0e2e8c0f8c08"]
    )]
    #[Assert\Type(type: 'array', message: 'Floatgroups must be an array.')]
    #[Assert\All([
        new Assert\NotBlank(message: "Each Floatgroup should not be blank."),
    ])]
    protected ?array $floatgroups = null;

    #[Property(
        property: "os",
        description: "The proxmox OS from node.",
        type: "string",
        example: "Debian 12"
    )]
    #[Assert\Type("string")]
    protected ?string $os = null;
    
    /**
     * @return array
     */
    public function floatgroups(): ?array
    {
        return $this->floatgroups;
    }

    /**
     * @return mixed
     */
    public function gcp_node_name(): ?string
    {
        return $this->gcp_node_name;
    }

    /**
     * @return mixed
     */
    public function pve_node_name(): ?string
    {
        return $this->pve_node_name;
    }

    /**
     * @return mixed
     */
    public function pve_hostname(): ?string
    {
        return $this->pve_hostname;
    }

    /**
     * @return mixed
     */
    public function pve_username(): ?string
    {
        return $this->pve_username;
    }

    /**
     * @return mixed
     */
    public function pve_password(): ?string
    {
        return $this->pve_password;
    }

    /**
     * @return mixed
     */
    public function pve_realm(): ?string
    {
        return $this->pve_realm;
    }

    /**
     * @return mixed
     */
    public function pve_port(): ?int
    {
        return $this->pve_port;
    }

    /**
     * @return mixed
     */
    public function pve_ip(): ?string
    {
        return $this->pve_ip;
    }

    /**
     * @return mixed
     */
    public function sshPort(): ?int
    {
        return $this->ssh_port;
    }

    /**
     * @return mixed
     */
    public function timeZone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @return mixed
     */
    public function keyboard(): ?string
    {
        return $this->keyboard;
    }

    /**
     * @return mixed
     */
    public function display(): ?string
    {
        return $this->display;
    }

    /**
     * @return mixed
     */
    public function storage(): ?string
    {
        return $this->storage;
    }

    /**
     * @return mixed
     */
    public function storageIso(): ?string
    {
        return $this->storage_iso;
    }

    /**
     * @return mixed
     */
    public function storageImage(): ?string
    {
        return $this->storage_image;
    }

    /**
     * @return mixed
     */
    public function storageBackup(): ?string
    {
        return $this->storage_backup;
    }

    /**
     * @return mixed
     */
    public function networkInterface(): ?string
    {
        return $this->network_interface;
    }

    public function cpu(): array | string | null
    {
        return $this->cpu;
    }
    /**
     * @return int
     */
    public function priority(): int
    {
        return $this->priority;
    }
    public function os(): ?string
    {
        return $this->os;
    }
}