<?php
declare(strict_types=1);

namespace GridCP\Proxmox\Os\Application\Response;

use GridCP\Common\Domain\Bus\Query\Response;

final readonly class OsResponse implements Response
{
    public function __construct(
        private ?string $uuid = null,
        private ?string $name = null,
        private ?string $tag = null,
        private ?string $image = null,
        private ?string $username = null,
        private ?int $id = null,
    ) {
    }
    
    public function uuid(): ?string
    {
        return $this->uuid;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function tag(): ?string
    {
        return $this->tag;
    }

    public function image():?string
    {
        return $this->image;
    }

    public function username():?string
    {
        return $this->username;
    }
}
