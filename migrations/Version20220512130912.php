<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220512130912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_slot ADD item_id INT NOT NULL, ADD quantity INT NOT NULL');
        $this->addSql('ALTER TABLE inventory_slot ADD CONSTRAINT FK_E6A8EF49126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6A8EF49126F525E ON inventory_slot (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_slot DROP FOREIGN KEY FK_E6A8EF49126F525E');
        $this->addSql('DROP INDEX UNIQ_E6A8EF49126F525E ON inventory_slot');
        $this->addSql('ALTER TABLE inventory_slot DROP item_id, DROP quantity');
    }
}
