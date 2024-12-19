<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218200916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP CONSTRAINT fk_c744045567b3b43d');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP INDEX uniq_c744045567b3b43d');
        $this->addSql('ALTER TABLE client ADD nom VARCHAR(200) DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD prenom VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD email VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE client DROP users_id');
        $this->addSql('ALTER TABLE client DROP surname');
        $this->addSql('ALTER TABLE client DROP adresse');
        $this->addSql('ALTER TABLE client DROP create_at');
        $this->addSql('ALTER TABLE client DROP update_at');
        $this->addSql('ALTER TABLE client ALTER telephone DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, login VARCHAR(100) NOT NULL, password VARCHAR(25) NOT NULL, create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_blocked BOOLEAN NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_8d93d649aa08cb10 ON "user" (login)');
        $this->addSql('COMMENT ON COLUMN "user".create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE client ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD surname VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE client ADD adresse VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE client ADD create_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE client ADD update_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE client DROP nom');
        $this->addSql('ALTER TABLE client DROP prenom');
        $this->addSql('ALTER TABLE client DROP email');
        $this->addSql('ALTER TABLE client ALTER telephone SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN client.create_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN client.update_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT fk_c744045567b3b43d FOREIGN KEY (users_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_c744045567b3b43d ON client (users_id)');
    }
}
