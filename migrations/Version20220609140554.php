<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609140554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DE48EFE78');
        $this->addSql('DROP INDEX IDX_6EEAA67DE48EFE78 ON commande');
        $this->addSql('ALTER TABLE commande DROP selection_id');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27E48EFE78');
        $this->addSql('DROP INDEX IDX_29A5EC27E48EFE78 ON produit');
        $this->addSql('ALTER TABLE produit DROP selection_id');
        $this->addSql('ALTER TABLE selection ADD commande_id INT DEFAULT NULL, ADD produit_id INT DEFAULT NULL, DROP quantite');
        $this->addSql('ALTER TABLE selection ADD CONSTRAINT FK_96A50CD782EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE selection ADD CONSTRAINT FK_96A50CD7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_96A50CD782EA2E54 ON selection (commande_id)');
        $this->addSql('CREATE INDEX IDX_96A50CD7F347EFB ON selection (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD selection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DE48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DE48EFE78 ON commande (selection_id)');
        $this->addSql('ALTER TABLE produit ADD selection_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27E48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27E48EFE78 ON produit (selection_id)');
        $this->addSql('ALTER TABLE selection DROP FOREIGN KEY FK_96A50CD782EA2E54');
        $this->addSql('ALTER TABLE selection DROP FOREIGN KEY FK_96A50CD7F347EFB');
        $this->addSql('DROP INDEX IDX_96A50CD782EA2E54 ON selection');
        $this->addSql('DROP INDEX IDX_96A50CD7F347EFB ON selection');
        $this->addSql('ALTER TABLE selection ADD quantite INT NOT NULL, DROP commande_id, DROP produit_id');
    }
}
