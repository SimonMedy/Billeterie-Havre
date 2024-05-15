<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515074237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, id_type_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', description VARCHAR(255) NOT NULL, age_restrict INT NOT NULL, annule TINYINT(1) NOT NULL, message VARCHAR(255) NOT NULL, INDEX IDX_3BAE0AA71BD125E3 (id_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_utilisateur (event_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_B695B03371F7E88B (event_id), INDEX IDX_B695B033FB88E14F (utilisateur_id), PRIMARY KEY(event_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA71BD125E3 FOREIGN KEY (id_type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE event_utilisateur ADD CONSTRAINT FK_B695B03371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_utilisateur ADD CONSTRAINT FK_B695B033FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA71BD125E3');
        $this->addSql('ALTER TABLE event_utilisateur DROP FOREIGN KEY FK_B695B03371F7E88B');
        $this->addSql('ALTER TABLE event_utilisateur DROP FOREIGN KEY FK_B695B033FB88E14F');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_utilisateur');
        $this->addSql('DROP TABLE type');
    }
}
