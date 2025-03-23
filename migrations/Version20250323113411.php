<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use stdClass;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250323113411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    private string $wifiName = '';
    private string $portalUrl = '';

    public function preUp(Schema $schema): void {
        $stmt = $this->connection->prepare('SELECT * FROM setting');
        $result = $stmt->executeQuery();
        while($row = $result->fetchAssociative()) {
            if($row['key'] === 'wifi.name') {
                $this->wifiName = unserialize($row['value']);
            } else if($row['key'] === 'wifi.portal.url') {
                $this->portalUrl = unserialize($row['value']);
            }
        }
    }

    public function postUp(Schema $schema): void {
        $obj = new stdClass();
        $obj->wifiName = $this->wifiName;
        $obj->portalUrl = $this->portalUrl;

        $stmt = $this->connection->prepare('INSERT INTO setting (`key`, data) VALUES (:key, :data)');
        $stmt->bindValue('key', 'wifi');
        $stmt->bindValue('data', json_encode($obj));
        $stmt->executeQuery();
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting ADD id INT UNSIGNED AUTO_INCREMENT NOT NULL, ADD `data` JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', DROP value, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9F74B8984E645A7E ON setting (`key`)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting MODIFY id INT UNSIGNED NOT NULL');
        $this->addSql('DROP INDEX UNIQ_9F74B8984E645A7E ON setting');
        $this->addSql('DROP INDEX `PRIMARY` ON setting');
        $this->addSql('ALTER TABLE setting ADD value LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', DROP id, DROP `data`');
        $this->addSql('ALTER TABLE setting ADD PRIMARY KEY (`key`)');
    }
}
