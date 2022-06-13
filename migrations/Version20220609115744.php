<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609115744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix bank account number length (longer for IBAN)';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person CHANGE bank_account_number bank_account_number VARCHAR(28) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person CHANGE bank_account_number bank_account_number VARCHAR(26) DEFAULT NULL');
    }
}
