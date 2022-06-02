<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220602120409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE critique (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, produit_id INT NOT NULL, note DOUBLE PRECISION NOT NULL, contenu LONGTEXT DEFAULT NULL, create_at DATETIME NOT NULL, INDEX IDX_1F950324FB88E14F (utilisateur_id), INDEX IDX_1F950324F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE critique ADD CONSTRAINT FK_1F950324FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE critique ADD CONSTRAINT FK_1F950324F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE critique');
    }
}
