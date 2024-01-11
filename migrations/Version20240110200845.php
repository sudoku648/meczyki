<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110200845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club CHANGE id id CHAR(36) NOT NULL, CHANGE emblem_id emblem_id CHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE game_type CHANGE id id CHAR(36) NOT NULL, CHANGE image_id image_id CHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE image CHANGE id id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE match_game CHANGE id id CHAR(36) NOT NULL, CHANGE user_id user_id CHAR(36) DEFAULT NULL, CHANGE home_team_id home_team_id CHAR(36) DEFAULT NULL, CHANGE away_team_id away_team_id CHAR(36) DEFAULT NULL, CHANGE game_type_id game_type_id CHAR(36) DEFAULT NULL, CHANGE referee_id referee_id CHAR(36) DEFAULT NULL, CHANGE first_assistant_referee_id first_assistant_referee_id CHAR(36) DEFAULT NULL, CHANGE second_assistant_referee_id second_assistant_referee_id CHAR(36) DEFAULT NULL, CHANGE fourth_official_id fourth_official_id CHAR(36) DEFAULT NULL, CHANGE referee_observer_id referee_observer_id CHAR(36) DEFAULT NULL, CHANGE delegate_id delegate_id CHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE match_game_bill CHANGE id id CHAR(36) NOT NULL, CHANGE person_id person_id CHAR(36) NOT NULL, CHANGE match_game_id match_game_id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE person CHANGE id id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE team CHANGE id id CHAR(36) NOT NULL, CHANGE club_id club_id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id CHAR(36) NOT NULL, CHANGE person_id person_id CHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_to_role CHANGE user_id user_id CHAR(36) NOT NULL, CHANGE user_role_id user_role_id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user_role CHANGE id id CHAR(36) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE club CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE emblem_id emblem_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE person CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE match_game CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_id user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE home_team_id home_team_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE away_team_id away_team_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE game_type_id game_type_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE referee_id referee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE first_assistant_referee_id first_assistant_referee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE second_assistant_referee_id second_assistant_referee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE fourth_official_id fourth_official_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE referee_observer_id referee_observer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE delegate_id delegate_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user_to_role CHANGE user_id user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_role_id user_role_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user_role CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE image CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE `user` CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE person_id person_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE game_type CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE image_id image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE team CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE club_id club_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE match_game_bill CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE person_id person_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE match_game_id match_game_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
    }
}
