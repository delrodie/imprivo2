<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923143622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(8) DEFAULT NULL, nom VARCHAR(128) DEFAULT NULL, telephone VARCHAR(22) DEFAULT NULL, email VARCHAR(96) DEFAULT NULL, rc VARCHAR(72) DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, ville VARCHAR(96) DEFAULT NULL, type VARCHAR(255) NOT NULL, actif TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_by VARCHAR(128) DEFAULT NULL, UNIQUE INDEX UNIQ_C7440455D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, contact_client_id INT DEFAULT NULL, uuid BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', numero VARCHAR(50) DEFAULT NULL, date DATETIME DEFAULT NULL, total_ht INT DEFAULT NULL, total_tva INT DEFAULT NULL, total_ttc INT DEFAULT NULL, statut VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_by VARCHAR(128) DEFAULT NULL, note_interne LONGTEXT DEFAULT NULL, note_client LONGTEXT DEFAULT NULL, remise DOUBLE PRECISION DEFAULT NULL, taux_tva DOUBLE PRECISION DEFAULT NULL, sended_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', sended_by VARCHAR(128) DEFAULT NULL, INDEX IDX_8B27C52B19EB6921 (client_id), INDEX IDX_8B27C52B771A4A5A (contact_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_ligne (id INT AUTO_INCREMENT NOT NULL, devis_id INT DEFAULT NULL, uom_id INT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, quantite INT DEFAULT NULL, prix_unitaire INT DEFAULT NULL, montant INT DEFAULT NULL, details JSON DEFAULT NULL, INDEX IDX_41D3C6A741DEFADA (devis_id), INDEX IDX_41D3C6A7A103EEB1 (uom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', nom VARCHAR(64) DEFAULT NULL, prenom VARCHAR(120) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, telephone VARCHAR(15) DEFAULT NULL, code VARCHAR(6) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(96) DEFAULT NULL, updated_by VARCHAR(96) DEFAULT NULL, UNIQUE INDEX UNIQ_F804D3B9D17F50A6 (uuid), UNIQUE INDEX UNIQ_F804D3B9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(100) NOT NULL, label VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_E04992AA77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representant (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, uuid BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', nom VARCHAR(32) DEFAULT NULL, prenom VARCHAR(128) DEFAULT NULL, fonction VARCHAR(72) DEFAULT NULL, telephone1 VARCHAR(15) DEFAULT NULL, telephone2 VARCHAR(15) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(128) DEFAULT NULL, updated_by VARCHAR(128) DEFAULT NULL, UNIQUE INDEX UNIQ_80D5DBC9D17F50A6 (uuid), INDEX IDX_80D5DBC919EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sequence_doc (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, prefix VARCHAR(10) DEFAULT NULL, format VARCHAR(50) DEFAULT NULL, compteur INT DEFAULT NULL, annee INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite_mesure (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(10) DEFAULT NULL, libelle VARCHAR(100) DEFAULT NULL, symbole VARCHAR(10) DEFAULT NULL, actif TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, connexion INT DEFAULT NULL, last_connected_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_permission (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, permission_id INT DEFAULT NULL, INDEX IDX_472E5446A76ED395 (user_id), INDEX IDX_472E5446FED90CCA (permission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B771A4A5A FOREIGN KEY (contact_client_id) REFERENCES representant (id)');
        $this->addSql('ALTER TABLE devis_ligne ADD CONSTRAINT FK_41D3C6A741DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE devis_ligne ADD CONSTRAINT FK_41D3C6A7A103EEB1 FOREIGN KEY (uom_id) REFERENCES unite_mesure (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE representant ADD CONSTRAINT FK_80D5DBC919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_permission ADD CONSTRAINT FK_472E5446FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B771A4A5A');
        $this->addSql('ALTER TABLE devis_ligne DROP FOREIGN KEY FK_41D3C6A741DEFADA');
        $this->addSql('ALTER TABLE devis_ligne DROP FOREIGN KEY FK_41D3C6A7A103EEB1');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9A76ED395');
        $this->addSql('ALTER TABLE representant DROP FOREIGN KEY FK_80D5DBC919EB6921');
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446A76ED395');
        $this->addSql('ALTER TABLE user_permission DROP FOREIGN KEY FK_472E5446FED90CCA');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_ligne');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE representant');
        $this->addSql('DROP TABLE sequence_doc');
        $this->addSql('DROP TABLE unite_mesure');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_permission');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
