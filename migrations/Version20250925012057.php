<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250925012057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, devis_id INT DEFAULT NULL, uuid BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', numero VARCHAR(50) DEFAULT NULL, date DATETIME DEFAULT NULL, total_ht INT DEFAULT NULL, total_tva INT DEFAULT NULL, total_ttc INT DEFAULT NULL, taux_tva DOUBLE PRECISION DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, statut VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_by VARCHAR(128) DEFAULT NULL, INDEX IDX_FE86641019EB6921 (client_id), UNIQUE INDEX UNIQ_FE86641041DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture_ligne (id INT AUTO_INCREMENT NOT NULL, uom_id INT DEFAULT NULL, facture_id INT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, quantite INT DEFAULT NULL, prix_unitaire INT DEFAULT NULL, montant INT DEFAULT NULL, detais JSON DEFAULT NULL, INDEX IDX_C5C45334A103EEB1 (uom_id), INDEX IDX_C5C453347F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture_log (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, action VARCHAR(50) DEFAULT NULL, auteur VARCHAR(128) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EA4FA58A7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641041DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE facture_ligne ADD CONSTRAINT FK_C5C45334A103EEB1 FOREIGN KEY (uom_id) REFERENCES unite_mesure (id)');
        $this->addSql('ALTER TABLE facture_ligne ADD CONSTRAINT FK_C5C453347F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE facture_log ADD CONSTRAINT FK_EA4FA58A7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641041DEFADA');
        $this->addSql('ALTER TABLE facture_ligne DROP FOREIGN KEY FK_C5C45334A103EEB1');
        $this->addSql('ALTER TABLE facture_ligne DROP FOREIGN KEY FK_C5C453347F2DEE08');
        $this->addSql('ALTER TABLE facture_log DROP FOREIGN KEY FK_EA4FA58A7F2DEE08');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE facture_ligne');
        $this->addSql('DROP TABLE facture_log');
    }
}
