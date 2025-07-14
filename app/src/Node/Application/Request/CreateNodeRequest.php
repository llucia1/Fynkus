<?php
declare(strict_types=1);

namespace GridCP\Node\Application\Request;

use GridCP\Common\Application\BaseRequest;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Attributes as OA;

#[Schema(
    schema: "CreateNodeRequest",
    title: "Create Node Request",
    description: "Request schema for creating a new node.",
    required: [],
    type: "object",
)]
class CreateNodeRequest extends BaseRequest
{
    #[Property(
        property: "gcp_node_name",
        description: "The name of the node GPC.",
        type: "string",
        example: "node1"
    )]

    #[NotBlank(message: 'Name should not be blank')]
    protected string $gcp_node_name;

    #[Property(
        property: "pve_node_name",
        description: "The name of the node PVE.",
        type: "string",
        example: "node1"
    )]

    #[NotBlank(message: 'Name should not be blank')]
    protected string $pve_node_name;

    #[Property(
        property: "pve_hostname",
        description: "The hostname of the node.",
        type: "string",
        example: "node1"
    )]
    #[NotBlank(message: 'Hostname should not be blank')]
    protected string $pve_hostname;

    #[Property(
        property: "pve_username",
        description: "The PVE username from node.",
        type: "string",
        example: "root"
    )]
    #[NotBlank(message: 'Username should not be blank')]
    protected string $pve_username;

    #[Property(
        property: "pve_password",
        description: "The proxmox password from node.",
        type: "string",
        example: "root"
    )]
    #[NotBlank(message: 'Password should not be blank')]
    protected string $pve_password;


    #[Property(
        property: "os",
        description: "The proxmox OS from node.",
        type: "string",
        example: "Debian 12"
    )]
    #[NotBlank(message: 'OS name should not be blank')]
    #[Assert\Type("string")]
    protected ?string $os = null;
    
    #[Property(
        property: "pve_realm",
        description: "The proxmox realm from node.",
        type: "string",
        example: "pam"
    )]
    #[NotBlank(message: 'Realm should not be blank')]
    protected string $pve_realm;

    #[Property(
        property: "pve_port",
        description: "The proxmox port from node.",
        type: "integer",
        example: "8006"
    )]
    #[NotBlank(message: 'Port should not be blank')]
    #[Assert\Type(type: 'integer', message: 'The PROXMOX port must be an integer.')]
    #[Assert\Range(
        min: 1,
        max: 65535,
        notInRangeMessage: 'The PROXMOX port must be in the range of 1 to 65535.'
    )]
    protected int $pve_port;

    #[Property(
        property: "pve_ip",
        description: "The IP address of the node in IPv4 format.",
        type: "string",
        format: "ipv4",
        example: "192.168.1.2"// NOSONAR
    )]
    #[NotBlank(message: 'IP should not be blank')]
    #[Ip(version:"4", message:"Invalid IP format")]
    protected string $pve_ip;

    #[Property(
        property: "ssh_port",
        description: "The SSH port of the node.",
        type: "integer",
        example: 22
    )]
    #[Assert\Type(type: 'integer', message: 'The SSH port must be an integer.')]
    #[Assert\Range(
        min: 1,
        max: 65535,
        notInRangeMessage: 'The SSH port must be in the range of 1 to 65535.'
    )]
    protected int $ssh_port=0;

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
    protected array $floatgroups = [];
    
    /**
     * @return array
     */
    public function floatgroups(): array
    {
        return $this->floatgroups;
    }



    /**
     * @return string
     */
    public function gcp_node_name(): string
    {
        return $this->gcp_node_name;
    }

    /**
     * @return string
     */
    public function pve_node_name(): string
    {
        return $this->pve_node_name;
    }

    /**
     * @return string
     */
    public function pve_hostname(): string
    {
        return $this->pve_hostname;
    }

    /**
     * @return string
     */
    public function pve_username(): string
    {
        return $this->pve_username;
    }

    /**
     * @return string
     */
    public function pve_password(): string
    {
        return $this->pve_password;
    }

    /**
     * @return mixed
     */
    public function pve_realm(): string
    {
        return $this->pve_realm;
    }

    /**
     * @return int
     */
    public function pve_port(): int
    {
        return $this->pve_port;
    }

    /**
     * @return string
     */
    public function pve_ip(): string
    {
        return $this->pve_ip;
    }

    /**
     * @return int
     */
    public function sshPort(): int
    {
        return $this->ssh_port;
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