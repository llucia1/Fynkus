<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241016CreateTables extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void// NOSONAR
    {
        // Create tables


        $this->addSql('CREATE TABLE IF NOT EXISTS node (
        id BIGINT AUTO_INCREMENT NOT NULL,
        uuid VARCHAR(36) NOT NULL,
        gcp_node_name VARCHAR(250) NOT NULL,
        pve_node_name VARCHAR(250) NOT NULL,
        pve_hostname VARCHAR(250) NOT NULL,
        pve_username VARCHAR(250) NOT NULL,
        pve_password VARCHAR(250) NOT NULL,
        pve_realm VARCHAR(30) NOT NULL,
        pve_port INT not null ,
        pve_ip VARCHAR(20) NOT NULL,
        ssh_port INT DEFAULT NULL,
        timezone VARCHAR(50) DEFAULT NULL,
        keyboard VARCHAR(3) DEFAULT NULL,
        display VARCHAR(20) DEFAULT NULL,
        storage VARCHAR(255) DEFAULT NULL,
        storage_iso VARCHAR(255) DEFAULT NULL,
        storage_image VARCHAR(255) DEFAULT NULL,
        storage_backup VARCHAR(255) DEFAULT NULL,
        network_interface VARCHAR(255) DEFAULT NULL,
        active BOOL NOT NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME DEFAULT NULL,
        UNIQUE INDEX UNIQ_NODE_UUID (uuid),
        PRIMARY KEY(id)
        ) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci');

    }
}