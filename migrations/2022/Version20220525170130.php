<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220525170130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'GameType entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE game_type (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, `name` VARCHAR(150) NOT NULL, `group` VARCHAR(150) DEFAULT NULL, is_official TINYINT(1) NOT NULL, INDEX IDX_67CB3B053DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_type ADD CONSTRAINT FK_67CB3B053DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE game_type');
    }
}
