<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605200011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE service_connection (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ip VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE service_job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, connection_id INTEGER DEFAULT NULL, command_id INTEGER DEFAULT NULL, is_active BOOLEAN NOT NULL, result VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE INDEX IDX_D988938FDD03F01 ON service_job (connection_id)');
        $this->addSql('CREATE INDEX IDX_D988938F33E1689A ON service_job (command_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE service_command');
        $this->addSql('DROP TABLE service_connection');
        $this->addSql('DROP TABLE service_job');
    }
}
