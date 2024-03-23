<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210104214648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE id_entity (entity_id VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, expiry DATETIME NOT NULL, PRIMARY KEY(entity_id, id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, level INT NOT NULL, message LONGTEXT NOT NULL, time DATETIME NOT NULL, details LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (`key` VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', PRIMARY KEY(`key`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, idp_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', data LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wifi_code (id INT UNSIGNED AUTO_INCREMENT NOT NULL, requested_by_id INT UNSIGNED DEFAULT NULL, code VARCHAR(255) NOT NULL, duration INT NOT NULL, requested_at DATETIME DEFAULT NULL, comment LONGTEXT DEFAULT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_96E091F177153098 (code), INDEX IDX_96E091F14DA1E751 (requested_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wifi_code ADD CONSTRAINT FK_96E091F14DA1E751 FOREIGN KEY (requested_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wifi_code DROP FOREIGN KEY FK_96E091F14DA1E751');
        $this->addSql('DROP TABLE id_entity');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wifi_code');
    }
}
