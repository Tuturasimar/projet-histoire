<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220512130627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archetype (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, pv INT NOT NULL, pm INT NOT NULL, strength INT NOT NULL, agility INT NOT NULL, intelligence INT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chapter (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, trigger_choice VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `character` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, archetype_id INT NOT NULL, pj_id INT NOT NULL, pv INT NOT NULL, pm INT NOT NULL, strength INT NOT NULL, agility INT NOT NULL, intelligence INT NOT NULL, alignment INT NOT NULL, story_done LONGTEXT NOT NULL, INDEX IDX_937AB034A76ED395 (user_id), INDEX IDX_937AB034732C6CC7 (archetype_id), INDEX IDX_937AB034F26B1C97 (pj_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choice (id INT AUTO_INCREMENT NOT NULL, scene_id INT NOT NULL, constraints VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL, effect VARCHAR(255) NOT NULL, next_story VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_C1AB5A92166053B4 (scene_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory (id INT AUTO_INCREMENT NOT NULL, charact_id INT NOT NULL, UNIQUE INDEX UNIQ_B12D4A3639306F15 (charact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventory_slot (id INT AUTO_INCREMENT NOT NULL, inventory_id INT NOT NULL, INDEX IDX_E6A8EF499EEA759 (inventory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pj (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, pv INT NOT NULL, pm INT NOT NULL, strength INT NOT NULL, agility INT NOT NULL, intelligence INT NOT NULL, background LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scene (id INT AUTO_INCREMENT NOT NULL, chapter_id INT NOT NULL, content LONGTEXT NOT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_D979EFDA579F4768 (chapter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034732C6CC7 FOREIGN KEY (archetype_id) REFERENCES archetype (id)');
        $this->addSql('ALTER TABLE `character` ADD CONSTRAINT FK_937AB034F26B1C97 FOREIGN KEY (pj_id) REFERENCES pj (id)');
        $this->addSql('ALTER TABLE choice ADD CONSTRAINT FK_C1AB5A92166053B4 FOREIGN KEY (scene_id) REFERENCES scene (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A3639306F15 FOREIGN KEY (charact_id) REFERENCES `character` (id)');
        $this->addSql('ALTER TABLE inventory_slot ADD CONSTRAINT FK_E6A8EF499EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id)');
        $this->addSql('ALTER TABLE scene ADD CONSTRAINT FK_D979EFDA579F4768 FOREIGN KEY (chapter_id) REFERENCES chapter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034732C6CC7');
        $this->addSql('ALTER TABLE scene DROP FOREIGN KEY FK_D979EFDA579F4768');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A3639306F15');
        $this->addSql('ALTER TABLE inventory_slot DROP FOREIGN KEY FK_E6A8EF499EEA759');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034F26B1C97');
        $this->addSql('ALTER TABLE choice DROP FOREIGN KEY FK_C1AB5A92166053B4');
        $this->addSql('ALTER TABLE `character` DROP FOREIGN KEY FK_937AB034A76ED395');
        $this->addSql('DROP TABLE archetype');
        $this->addSql('DROP TABLE chapter');
        $this->addSql('DROP TABLE `character`');
        $this->addSql('DROP TABLE choice');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('DROP TABLE inventory_slot');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE pj');
        $this->addSql('DROP TABLE scene');
        $this->addSql('DROP TABLE user');
    }
}
