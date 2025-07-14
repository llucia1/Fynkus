<?php
declare(strict_types=1);

namespace GridCP\Node\Infrastructure\DB\MySQL\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use GridCP\Node\Domain\Model\NodeModel;
use GridCP\Node\Infrastructure\DB\MySQL\Repository\NodeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid as Ramsey;
use GridCP\Common\Domain\Const\Node\Constants;
use GridCP\Net\Common\Infrastructure\DB\MySQL\Entity\Ip4FloatGroupEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use GridCP\Proxmox\Os\Infrastructure\DB\MySQL\Entity\OsEntity;

#[ORM\Entity(repositoryClass: NodeRepository::class)]
#[ORM\Table(name:'node')]
class NodeEntity extends  NodeModel
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string', length: 36, unique: true)]
    public ?string $uuid;

    #[ORM\Column(type: 'string', length: 250, nullable: false)]
    public ?string $gcp_node_name;

    #[ORM\Column(type: 'string', length: 250, nullable: false)]
    public ?string $pve_node_name;

    #[ORM\Column(type: 'string', length: 250, nullable: false)]
    public ?string $pve_hostname;

    #[ORM\Column(type: 'string', length: 250, nullable: false)]
    public ?string $pve_username;

    #[ORM\Column(type: 'string', length: 250, nullable: false)]
    public ?string $pve_password;

    #[ORM\Column(type: 'string', length: 30, nullable: false)]
    public ?string $pve_realm;

    #[ORM\Column(type: 'integer', nullable: false)]
    public ?int $pve_port;

    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    public ?string $pve_ip;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $ssh_port;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    public ?string $timezone;

    #[ORM\Column(type: 'string', length: 3, nullable: true)]
    public ?string $keyboard;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    public ?string $display;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    public ?string $storage;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    public ?string $storage_iso;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    public ?string $storage_image;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    public ?string $storage_backup;

    #[ORM\Column(type: 'string', length: 20,)]
    public ?string $network_interface;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    #[ORM\Column(type: 'json')]
    public ?array $cpu;

    #[ORM\Column(type: 'integer')]
    #[Assert\Range(min: 0, max: 10, notInRangeMessage: "Priority must be between {{ min }} and {{ max }}.")]
    public int $priority = 0;

    #[ORM\OneToMany(mappedBy: 'node', targetEntity: NodeFloatGroupEntity::class, cascade: ['persist', 'remove'])]
    public Collection $nodeFloatGroups;


    #[ORM\ManyToOne(targetEntity: OsEntity::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'id_os', referencedColumnName: 'id')]
    public ?OsEntity $os = null;

    public function __construct()
    {
        $this->uuid = Ramsey::uuid4()->toString();
        $this->nodeFloatGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid( ?string $uuid): ?static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getGcpName(): ?string
    {
        return $this->gcp_node_name;
    }

    public function setGcpName(?string $gcp_node_name): static
    {
        $this->gcp_node_name = $gcp_node_name;

        return $this;
    }

    public function getPveName(): ?string
    {
        return $this->pve_node_name;
    }

    public function setPveName(?string $pve_node_name): static
    {
        $this->pve_node_name = $pve_node_name;

        return $this;
    }

    public function getPveHostName(): ?string
    {
        return $this->pve_hostname;
    }

    public function setPveHostName(?string $hostname): static
    {
        $this->pve_hostname = $hostname;

        return $this;
    }

    public function getPveUserName(): ?string
    {
        return $this->pve_username;
    }

    public function setPveUserName(?string $username): static
    {
        $this->pve_username = $username;
        return $this;
    }

    public function getPvePassword(): string
    {
        return $this->pve_password;
    }

    public function setPvePassword(?string $password): static
    {
        $this->pve_password = $password;
        return $this;
    }

    public function getPveRealm(): string
    {
        return $this->pve_realm;
    }

    public function setPveRealm(?string $realm): static
    {
        $this->pve_realm = $realm;
        return $this;
    }

    public function getPvePort():int
    {
        return $this->pve_port;
    }

    public function setPvePort(?int $port):static
    {
        $this->pve_port = $port;
        return $this;
    }

    public function getPveIp(): ?string
    {
        return $this->pve_ip;
    }

    public function setPveIp(?string $pve_ip): static
    {
        $this->pve_ip = $pve_ip;

        return $this;
    }

    public function getSshPort(): ?int
    {
        return $this->ssh_port;
    }

    public function setSshPort(?int $ssh_port): static
    {
        $this->ssh_port = $this->valueNotSet($ssh_port);

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): static
    {
        $this->timezone = $this->valueNotSet($timezone);

        return $this;
    }

    public function getKeyboard(): ?string
    {
        return $this->keyboard;
    }

    public function setKeyboard(?string $keyboard): static
    {
        $this->keyboard = $this->valueNotSet($keyboard);

        return $this;
    }

    public function getDisplay(): ?string
    {
        return $this->display;
    }

    public function setDisplay(?string $display): static
    {
        $this->display = $this->valueNotSet($display);

        return $this;
    }

    public function getStorage(): ?string
    {
        return $this->storage;
    }

    public function setStorage(?string $storage): static
    {
        $this->storage = $this->valueNotSet($storage);

        return $this;
    }

    public function getStorageIso(): ?string
    {
        return $this->storage_iso;
    }

    public function setStorageIso(?string $storage_iso): static
    {
        $this->storage_iso = $this->valueNotSet($storage_iso);

        return $this;
    }

    public function getStorageImage(): ?string
    {
        return $this->storage_image;
    }

    public function setStorageImage(?string $storage_image): static
    {
        $this->storage_image = $this->valueNotSet($storage_image);

        return $this;
    }

    public function getStorageBackup(): ?string
    {
        return $this->storage_backup;
    }

    public function setStorageBackup(?string $storage_backup): static
    {
        $this->storage_backup = $this->valueNotSet($storage_backup);

        return $this;
    }

    public function getNetworkInterface(): ?string
    {
        return $this->network_interface;
    }

    public function setNetworkInterface(?string $network_interface): static
    {
        $this->network_interface = $this->valueNotSet($network_interface);

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): static
    {
        $this->active = $this->valueNotSet($active);

        return $this;
    }
    
    public function getCpu(): ?array
    {
        return $this->cpu;
    }

    public function setCpu(array | string | null $cpu): static
    {
        if ($this->valueNotSet($cpu)) {
            (isset($cpu['name']))?  $this->cpu['name'] = $cpu['name'] : null;
            (isset($cpu['vendor']))?  $this->cpu['vendor'] = $cpu['vendor'] : null;
            (isset($cpu['custom']))?  $this->cpu['custom'] = $cpu['custom'] : null;
        } else {
            $this->cpu = null;
        }
        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    private function valueNotSet($value): mixed
    {
        return ($value === Constants::NOT_SET) ? null : $value;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getFloatGroups(): ?Collection
    {
        return $this->nodeFloatGroups;
    }

    public function setFloatGroup(Ip4FloatGroupEntity $floatGroup): self
    {
        foreach ($this->nodeFloatGroups as $relation) {
            if ($relation->getFloatGroup() === $floatGroup) {
                return $this; // Ya existe la relación
            }
        }

        $relation = new NodeFloatGroupEntity($this, $floatGroup);
        $this->nodeFloatGroups->add($relation);
        return $this;
    }

    public function getOs(): ?osEntity
    {
        return $this->os;
    }
    
    public function setOs(?osEntity $os): self
    {
        $this->os = $os;
        return $this;
    }


}