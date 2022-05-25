<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220525094010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Image entity. Image joined to club';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, file_path VARCHAR(255) NOT NULL, file_name VARCHAR(180) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, UNIQUE INDEX images_file_name_idx (file_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE club ADD emblem_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872D9923C03 FOREIGN KEY (emblem_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_B8EE3872D9923C03 ON club (emblem_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872D9923C03');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP INDEX IDX_B8EE3872D9923C03 ON club');
        $this->addSql('ALTER TABLE club DROP emblem_id');
    }
}
