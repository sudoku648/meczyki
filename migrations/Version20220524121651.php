<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220524121651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Person entity binding to user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, mobile_phone VARCHAR(12) DEFAULT NULL, is_delegate TINYINT(1) NOT NULL, is_referee TINYINT(1) NOT NULL, is_referee_observer TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', UNIQUE INDEX UNIQ_34DCD176AA92691 (mobile_phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649217BBB47 ON user (person_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP INDEX UNIQ_8D93D649217BBB47 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP person_id');
    }
}
