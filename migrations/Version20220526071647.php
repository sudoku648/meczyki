<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526071647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'MatchGame entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE match_game (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, home_team_id INT DEFAULT NULL, away_team_id INT DEFAULT NULL, game_type_id INT DEFAULT NULL, referee_id INT DEFAULT NULL, first_assistant_referee_id INT DEFAULT NULL, second_assistant_referee_id INT DEFAULT NULL, fourth_official_id INT DEFAULT NULL, referee_observer_id INT DEFAULT NULL, delegate_id INT DEFAULT NULL, date_time DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', season VARCHAR(10) DEFAULT NULL, round INT DEFAULT NULL, venue VARCHAR(150) NOT NULL, is_first_assistant_non_profitable TINYINT(1) DEFAULT NULL, is_second_assistant_non_profitable TINYINT(1) DEFAULT NULL, more_info VARCHAR(2000) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', INDEX IDX_424480FEA76ED395 (user_id), INDEX IDX_424480FE9C4C13F6 (home_team_id), INDEX IDX_424480FE45185D02 (away_team_id), INDEX IDX_424480FE508EF3BC (game_type_id), INDEX IDX_424480FE4A087CA2 (referee_id), INDEX IDX_424480FE494C36CA (first_assistant_referee_id), INDEX IDX_424480FE371C85E7 (second_assistant_referee_id), INDEX IDX_424480FEFFFD7D98 (fourth_official_id), INDEX IDX_424480FE516E156B (referee_observer_id), INDEX IDX_424480FE8A0BB485 (delegate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE9C4C13F6 FOREIGN KEY (home_team_id) REFERENCES team (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE45185D02 FOREIGN KEY (away_team_id) REFERENCES team (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE508EF3BC FOREIGN KEY (game_type_id) REFERENCES game_type (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE4A087CA2 FOREIGN KEY (referee_id) REFERENCES person (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE494C36CA FOREIGN KEY (first_assistant_referee_id) REFERENCES person (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE371C85E7 FOREIGN KEY (second_assistant_referee_id) REFERENCES person (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FEFFFD7D98 FOREIGN KEY (fourth_official_id) REFERENCES person (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE516E156B FOREIGN KEY (referee_observer_id) REFERENCES person (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE match_game ADD CONSTRAINT FK_424480FE8A0BB485 FOREIGN KEY (delegate_id) REFERENCES person (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE match_game');
    }
}
