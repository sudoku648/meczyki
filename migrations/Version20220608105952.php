<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220608105952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD email VARCHAR(150) DEFAULT NULL, ADD date_of_birth DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', ADD place_of_birth VARCHAR(100) DEFAULT NULL, ADD address_town VARCHAR(100) DEFAULT NULL, ADD address_street VARCHAR(100) DEFAULT NULL, ADD address_zip_code VARCHAR(6) DEFAULT NULL, ADD address_post_office VARCHAR(100) DEFAULT NULL, ADD address_voivodeship VARCHAR(100) DEFAULT NULL, ADD address_powiat VARCHAR(100) DEFAULT NULL, ADD address_gmina VARCHAR(100) DEFAULT NULL, ADD tax_office_name VARCHAR(150) DEFAULT NULL, ADD tax_office_address VARCHAR(150) DEFAULT NULL, ADD pesel VARCHAR(11) DEFAULT NULL, ADD nip VARCHAR(10) DEFAULT NULL, ADD bank_account_number VARCHAR(26) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP email, DROP date_of_birth, DROP place_of_birth, DROP address_town, DROP address_street, DROP address_zip_code, DROP address_post_office, DROP address_voivodeship, DROP address_powiat, DROP address_gmina, DROP tax_office_name, DROP tax_office_address, DROP pesel, DROP nip, DROP bank_account_number');
    }
}
