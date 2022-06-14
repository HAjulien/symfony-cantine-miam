<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609081354 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2449BA15FB88E14F ON equipe (utilisateur_id)');
        $this->addSql('ALTER TABLE produit ADD date_selection DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15FB88E14F');
        $this->addSql('DROP INDEX IDX_2449BA15FB88E14F ON equipe');
        $this->addSql('ALTER TABLE produit DROP date_selection');
    }
}
