<?php
declare(strict_types=1);

namespace GridCP\Node\Infrastructure\DB\MySQL\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

use Symfony\Component\Validator\Constraints as Assert;
use Ramsey\Uuid\Uuid as Ramsey;
use GridCP\Common\Domain\Const\Node\Constants;
use GridCP\Net\Common\Infrastructure\DB\MySQL\Entity\Ip4FloatGroupEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use GridCP\Node\Infrastructure\DB\MySQL\Repository\NodeFloatGroupRepository;

#[ORM\Entity(repositoryClass: NodeFloatGroupRepository::class)]
#[ORM\Table(name:'node_floatgroup')]
class NodeFloatGroupEntity
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: NodeEntity::class, inversedBy: 'nodeFloatGroups')]
    #[ORM\JoinColumn(name: 'node_id', referencedColumnName: 'id', nullable: false)]
    private ?NodeEntity $node = null;


    #[ORM\ManyToOne(targetEntity: Ip4FloatGroupEntity::class, inversedBy: 'nodeFloatGroups')]
    #[ORM\JoinColumn(name: 'floatgroup_id', referencedColumnName: 'id', nullable: false)]
    private Ip4FloatGroupEntity $floatGroup;

    #[ORM\Column(type: 'boolean')]
    private bool $active = true;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    public ?DateTimeInterface $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime')]
    public ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        
    }


    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function getNode(): ?NodeEntity
    {
        return $this->node;
    }

    public function setNode(NodeEntity $node): self
    {
        $this->node = $node;

        return $this;
    }

    public function getFloatGroup(): Ip4FloatGroupEntity
    {
        return $this->floatGroup;
    }

    public function setFloatgroup(Ip4FloatGroupEntity $floatgroup): self
    {
        $this->floatGroup = $floatgroup;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }


    public function setActive(bool $active): self
    {
        $this->active = $active;
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

    

}