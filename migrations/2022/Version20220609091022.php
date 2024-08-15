<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220609091022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Permission to send PIT by email option';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE person ADD allows_to_send_pit_by_email TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE person DROP allows_to_send_pit_by_email');
    }
}
