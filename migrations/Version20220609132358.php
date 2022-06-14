<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609132358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, selection_id INT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_6EEAA67DFB88E14F (utilisateur_id), INDEX IDX_6EEAA67DE48EFE78 (selection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE selection (id INT AUTO_INCREMENT NOT NULL, quantite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DFB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DE48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id)');
        $this->addSql('ALTER TABLE produit ADD selection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27E48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27E48EFE78 ON produit (selection_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DE48EFE78');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27E48EFE78');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE selection');
        $this->addSql('DROP INDEX IDX_29A5EC27E48EFE78 ON produit');
        $this->addSql('ALTER TABLE produit DROP selection_id');
    }
}
