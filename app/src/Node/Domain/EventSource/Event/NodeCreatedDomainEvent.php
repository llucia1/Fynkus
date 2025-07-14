<?php

namespace GridCP\Node\Domain\EventSource\Event;

use GridCP\Common\Domain\Bus\EventSource\DomainEvent;

class NodeCreatedDomainEvent extends DomainEvent
{
    public function __construct(
        string                  $id,
        private readonly string $uuid,
        private readonly string $name,
        private readonly string $hostName,
        private readonly string $ip,
        private readonly string $sshPort,
        private readonly string $timeZone,
        private readonly string $keyboard,
        private readonly string $display,
        private readonly string $storage,
        private readonly string $storageIso,
        private readonly string $storageImage,
        private readonly string $storageBackup,
        private readonly string $networkInterface,
        string                  $eventId = null,
        string                  $eventTime = null
    )
    {
        parent::__construct($id, $eventId, $eventTime);
    }

    public static function fromPrimitives(
        string $aggregateId,
        array  $body,
        string $eventId,
        string $eventTime
    ): DomainEvent
    {
        return new self($aggregateId, $body['uuid'], $body['name'], $body['hostName'], $body['ip'], $body['sshPort'], $body['timeZone'], $body['keyboard'], $body['display'], $body['storage'], $body['storageIso'], $body['storageImage'], $body['storageBackup'], $body['networkInterface'], $eventId, $eventTime);
    }

    public static function eventName(): string
    {
        return 'node.created';
    }

    public function toPrimitives(): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'hostName' => $this->hostName,
            'ip' => $this->ip,
            'sshPort' => $this->sshPort,
            'timeZone' => $this->timeZone,
            'keyboard' => $this->keyboard,
            'display' => $this->display,
            'storage' => $this->storage,
            'storageIso' => $this->storageIso,
            'storageImage' => $this->storageImage,
            'storageBackup' => $this->storageBackup,
            'networkInterface' => $this->networkInterface
        ];
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function hostName(): string
    {
        return $this->hostName;
    }

    public function ip(): string
    {
        return $this->ip;
    }

    public function sshPort(): string
    {
        return $this->sshPort;
    }

    public function timeZone(): string
    {
        return $this->timeZone;
    }

    public function keyboard(): string
    {
        return $this->keyboard;
    }

    public function display(): string
    {
        return $this->display;
    }

    public function storage(): string
    {
        return $this->storage;
    }

    public function storageIso(): string
    {
        return $this->storageIso;
    }

    public function storageImage(): string
    {
        return $this->storageImage;
    }

    public function storageBackup(): string
    {
        return $this->storageBackup;
    }

    public function networkInterface(): string
    {
        return $this->networkInterface;
    }
}