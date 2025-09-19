<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250919105836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, contact_client_id INT DEFAULT NULL, numero VARCHAR(50) DEFAULT NULL, date DATETIME DEFAULT NULL, total_ht INT DEFAULT NULL, total_tva INT DEFAULT NULL, total_ttc INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_by VARCHAR(128) DEFAULT NULL, note_interne LONGTEXT DEFAULT NULL, note_client LONGTEXT DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, INDEX IDX_8B27C52B19EB6921 (client_id), INDEX IDX_8B27C52B771A4A5A (contact_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_ligne (id INT AUTO_INCREMENT NOT NULL, devis_id INT DEFAULT NULL, uom_id INT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, quantite INT DEFAULT NULL, prix_unitaire INT DEFAULT NULL, montant INT DEFAULT NULL, details JSON DEFAULT NULL, INDEX IDX_41D3C6A741DEFADA (devis_id), INDEX IDX_41D3C6A7A103EEB1 (uom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B771A4A5A FOREIGN KEY (contact_client_id) REFERENCES representant (id)');
        $this->addSql('ALTER TABLE devis_ligne ADD CONSTRAINT FK_41D3C6A741DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE devis_ligne ADD CONSTRAINT FK_41D3C6A7A103EEB1 FOREIGN KEY (uom_id) REFERENCES unite_mesure (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B771A4A5A');
        $this->addSql('ALTER TABLE devis_ligne DROP FOREIGN KEY FK_41D3C6A741DEFADA');
        $this->addSql('ALTER TABLE devis_ligne DROP FOREIGN KEY FK_41D3C6A7A103EEB1');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_ligne');
    }
}
