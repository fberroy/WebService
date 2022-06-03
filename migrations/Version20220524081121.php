<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524081121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, ue_id INT DEFAULT NULL, nom_cours VARCHAR(150) NOT NULL, nb_heures SMALLINT DEFAULT NULL, type_cours VARCHAR(2) NOT NULL, nb_enseignants SMALLINT NOT NULL, nb_groupes SMALLINT NOT NULL, nom_groupe VARCHAR(255) DEFAULT NULL, INDEX IDX_FDCA8C9C62E883B1 (ue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cours_enseignant (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT DEFAULT NULL, cours_id INT DEFAULT NULL, voeux TINYINT(1) NOT NULL, enseigne TINYINT(1) NOT NULL, nb_heures_att INT DEFAULT NULL, nb_groupes_att INT DEFAULT NULL, validation TINYINT(1) DEFAULT NULL, annee_voeux INT DEFAULT NULL, INDEX IDX_845FDD88E455FCC0 (enseignant_id), INDEX IDX_845FDD887ECF78B0 (cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, identifiant VARCHAR(100) DEFAULT NULL, mot_de_passe VARCHAR(50) NOT NULL, mail VARCHAR(150) DEFAULT NULL, nb_uc SMALLINT DEFAULT NULL, nb_ucattribue SMALLINT DEFAULT NULL, nom_departement VARCHAR(100) DEFAULT NULL, statut_enseignant VARCHAR(30) DEFAULT NULL, acces_admin TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ue (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(150) NOT NULL, formation VARCHAR(100) NOT NULL, semestre VARCHAR(100) NOT NULL, statut VARCHAR(100) NOT NULL, effectif SMALLINT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C62E883B1 FOREIGN KEY (ue_id) REFERENCES ue (id)');
        $this->addSql('ALTER TABLE cours_enseignant ADD CONSTRAINT FK_845FDD88E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cours_enseignant ADD CONSTRAINT FK_845FDD887ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cours_enseignant DROP FOREIGN KEY FK_845FDD887ECF78B0');
        $this->addSql('ALTER TABLE cours_enseignant DROP FOREIGN KEY FK_845FDD88E455FCC0');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C62E883B1');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE cours_enseignant');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE ue');
    }
}
