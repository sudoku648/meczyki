<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240110162054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update MySQL server version';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
