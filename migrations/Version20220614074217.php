<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220614074217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Game type create and update dates';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game_type ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game_type DROP created_at, DROP updated_at');
    }
}
