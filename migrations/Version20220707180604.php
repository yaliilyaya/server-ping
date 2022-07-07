<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220707180604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_job_report (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, job_id INTEGER DEFAULT NULL, result VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_27A63B1BE04EA9 ON service_job_report (job_id)');
        $this->addSql('DROP INDEX IDX_D988938F33E1689A');
        $this->addSql('DROP INDEX IDX_D988938FDD03F01');
        $this->addSql('CREATE TEMPORARY TABLE __temp__service_job AS SELECT id, connection_id, command_id, result, data, is_active, status FROM service_job');
        $this->addSql('DROP TABLE service_job');
        $this->addSql('CREATE TABLE service_job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, connection_id INTEGER DEFAULT NULL, command_id INTEGER DEFAULT NULL, result VARCHAR(255) NOT NULL COLLATE BINARY, data CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , is_active BOOLEAN NOT NULL, status VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_D988938FDD03F01 FOREIGN KEY (connection_id) REFERENCES service_connection (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D988938F33E1689A FOREIGN KEY (command_id) REFERENCES service_command (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO service_job (id, connection_id, command_id, result, data, is_active, status) SELECT id, connection_id, command_id, result, data, is_active, status FROM __temp__service_job');
        $this->addSql('DROP TABLE __temp__service_job');
        $this->addSql('CREATE INDEX IDX_D988938F33E1689A ON service_job (command_id)');
        $this->addSql('CREATE INDEX IDX_D988938FDD03F01 ON service_job (connection_id)');
        $this->addSql('update service_job set result = "" where 1;');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE service_job_report');
        $this->addSql('DROP INDEX IDX_D988938FDD03F01');
        $this->addSql('DROP INDEX IDX_D988938F33E1689A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__service_job AS SELECT id, connection_id, command_id, result, data, is_active, status FROM service_job');
        $this->addSql('DROP TABLE service_job');
        $this->addSql('CREATE TABLE service_job (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, connection_id INTEGER DEFAULT NULL, command_id INTEGER DEFAULT NULL, result VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        , is_active BOOLEAN NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO service_job (id, connection_id, command_id, result, data, is_active, status) SELECT id, connection_id, command_id, result, data, is_active, status FROM __temp__service_job');
        $this->addSql('DROP TABLE __temp__service_job');
        $this->addSql('CREATE INDEX IDX_D988938FDD03F01 ON service_job (connection_id)');
        $this->addSql('CREATE INDEX IDX_D988938F33E1689A ON service_job (command_id)');
    }
}
