<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620130729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename bank account number to IBAN';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person CHANGE bank_account_number iban VARCHAR(28) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person CHANGE iban bank_account_number VARCHAR(28) DEFAULT NULL');
    }
}
