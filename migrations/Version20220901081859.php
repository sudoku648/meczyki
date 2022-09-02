<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901081859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Uuids instead of ids';
    }

    public function preUp(Schema $schema): void
    {
        $this->addSql('ALTER TABLE club ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id, ADD emblem_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER emblem_id');
        $this->addSql('ALTER TABLE game_type ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id, ADD image_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER image_id');
        $this->addSql('ALTER TABLE image ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id');
        $this->addSql('ALTER TABLE match_game ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id, ADD user_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER user_id, ADD home_team_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER home_team_id, ADD away_team_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER away_team_id, ADD game_type_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER game_type_id, ADD referee_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER referee_id, ADD first_assistant_referee_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER first_assistant_referee_id, ADD second_assistant_referee_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER second_assistant_referee_id, ADD fourth_official_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER fourth_official_id, ADD referee_observer_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER referee_observer_id, ADD delegate_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER delegate_id');
        $this->addSql('ALTER TABLE match_game_bill ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id, ADD person_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER person_id, ADD match_game_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER match_game_id');
        $this->addSql('ALTER TABLE person ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id');
        $this->addSql('ALTER TABLE team ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id, ADD club_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER club_id');
        $this->addSql('ALTER TABLE user ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id, ADD person_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER person_id');
        $this->addSql('ALTER TABLE user_to_role ADD user_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER user_id, ADD user_role_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER user_role_id');
        $this->addSql('ALTER TABLE user_role ADD guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\' AFTER id');

        $this->addSql('UPDATE club SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE game_type SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE image SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE match_game SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE match_game_bill SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE person SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE team SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE user SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');
        $this->addSql('UPDATE user_role SET guid = (SELECT LOWER(CONCAT(LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), \'-\', \'6\', LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', HEX(FLOOR(RAND() * 4 + 8)), LPAD(HEX(FLOOR(RAND() * 0x0fff)), 3, \'0\'), \'-\', LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'), LPAD(HEX(FLOOR(RAND() * 0xffff)), 4, \'0\'))))');

        $this->addSql('UPDATE club c SET c.emblem_guid = (SELECT i.guid FROM image i WHERE i.id = c.emblem_id)');
        $this->addSql('UPDATE game_type gt SET gt.image_guid = (SELECT i.guid FROM image i WHERE i.id = gt.image_id)');
        $this->addSql('UPDATE match_game mg SET
            mg.user_guid = (SELECT u.guid FROM user u WHERE u.id = mg.user_id),
            mg.home_team_guid = (SELECT t.guid FROM team t WHERE t.id = mg.home_team_id),
            mg.away_team_guid = (SELECT t.guid FROM team t WHERE t.id = mg.away_team_id),
            mg.game_type_guid = (SELECT gt.guid FROM game_type gt WHERE gt.id = mg.game_type_id),
            mg.referee_guid = (SELECT p.guid FROM person p WHERE p.id = mg.referee_id),
            mg.first_assistant_referee_guid = (SELECT p.guid FROM person p WHERE p.id = mg.first_assistant_referee_id),
            mg.second_assistant_referee_guid = (SELECT p.guid FROM person p WHERE p.id = mg.second_assistant_referee_id),
            mg.fourth_official_guid = (SELECT p.guid FROM person p WHERE p.id = mg.fourth_official_id),
            mg.referee_observer_guid = (SELECT p.guid FROM person p WHERE p.id = mg.referee_observer_id),
            mg.delegate_guid = (SELECT p.guid FROM person p WHERE p.id = mg.delegate_id)
        ');
        $this->addSql('UPDATE match_game_bill mgb SET mgb.person_guid = (SELECT p.guid FROM person p WHERE p.id = mgb.person_id), mgb.match_game_guid = (SELECT mg.guid FROM match_game mg WHERE mg.id = mgb.match_game_id)');
        $this->addSql('UPDATE team t SET t.club_guid = (SELECT c.guid FROM club c WHERE c.id = t.club_id)');
        $this->addSql('UPDATE user u SET u.person_guid = (SELECT p.guid FROM person p WHERE p.id = u.person_id)');
        $this->addSql('UPDATE user_to_role utr SET utr.user_guid = (SELECT u.guid FROM user u WHERE u.id = utr.user_id), utr.user_role_guid = (SELECT ur.guid FROM user_role ur WHERE ur.id = utr.user_role_id)');
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872D9923C03');
        $this->addSql('ALTER TABLE game_type DROP FOREIGN KEY FK_67CB3B053DA5256D');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FEA76ED395');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE9C4C13F6');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE45185D02');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE508EF3BC');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE4A087CA2');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE494C36CA');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE371C85E7');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FEFFFD7D98');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE516E156B');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE8A0BB485');
        $this->addSql('ALTER TABLE match_game_bill DROP FOREIGN KEY FK_88853964217BBB47');
        $this->addSql('ALTER TABLE match_game_bill DROP FOREIGN KEY FK_888539649329866A');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F61190A32');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('ALTER TABLE user_to_role DROP FOREIGN KEY FK_E88A85AFA76ED395');
        $this->addSql('ALTER TABLE user_to_role DROP FOREIGN KEY FK_E88A85AF8E0E3CA6');

        $this->addSql('DROP INDEX IDX_B8EE3872D9923C03 ON club');
        $this->addSql('DROP INDEX IDX_67CB3B053DA5256D ON game_type');
        $this->addSql('DROP INDEX IDX_424480FEA76ED395 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE9C4C13F6 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE45185D02 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE508EF3BC ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE4A087CA2 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE494C36CA ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE371C85E7 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FEFFFD7D98 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE516E156B ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE8A0BB485 ON match_game');
        $this->addSql('DROP INDEX IDX_88853964217BBB47 ON match_game_bill');
        $this->addSql('DROP INDEX IDX_888539649329866A ON match_game_bill');
        $this->addSql('DROP INDEX IDX_C4E0A61F61190A32 ON team');
        $this->addSql('DROP INDEX UNIQ_8D93D649217BBB47 ON user');
        $this->addSql('DROP INDEX IDX_E88A85AFA76ED395 ON user_to_role');
        $this->addSql('DROP INDEX IDX_E88A85AF8E0E3CA6 ON user_to_role');

        $this->addSql('ALTER TABLE club DROP id, DROP emblem_id');
        $this->addSql('ALTER TABLE game_type DROP id, DROP image_id');
        $this->addSql('ALTER TABLE image DROP id');
        $this->addSql('ALTER TABLE match_game DROP id, DROP user_id, DROP home_team_id, DROP away_team_id, DROP game_type_id, DROP referee_id, DROP first_assistant_referee_id, DROP second_assistant_referee_id, DROP fourth_official_id, DROP referee_observer_id, DROP delegate_id');
        $this->addSql('ALTER TABLE match_game_bill DROP id, DROP person_id, DROP match_game_id');
        $this->addSql('ALTER TABLE person DROP id');
        $this->addSql('ALTER TABLE team DROP id, DROP club_id');
        $this->addSql('ALTER TABLE user DROP id, DROP person_id');
        $this->addSql('ALTER TABLE user_to_role DROP user_id, DROP user_role_id');
        $this->addSql('ALTER TABLE user_role DROP id');

        $this->addSql('ALTER TABLE club CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE emblem_guid emblem_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE game_type CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE image_guid image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE image CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE match_game CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_guid user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE home_team_guid home_team_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE away_team_guid away_team_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE game_type_guid game_type_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE referee_guid referee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE first_assistant_referee_guid first_assistant_referee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE second_assistant_referee_guid second_assistant_referee_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE fourth_official_guid fourth_official_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE referee_observer_guid referee_observer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE delegate_guid delegate_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE match_game_bill CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE person_guid person_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE match_game_guid match_game_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE person CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE team CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE club_guid club_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE person_guid person_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user_to_role CHANGE user_guid user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_role_guid user_role_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user_role CHANGE guid id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');

        $this->addSql('ALTER TABLE club ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE game_type ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE image ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE match_game ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE match_game_bill ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE person ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE team ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user_role ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user_to_role ADD PRIMARY KEY (user_id, user_role_id)');

        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872D9923C03 FOREIGN KEY (emblem_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE game_type ADD CONSTRAINT FK_67CB3B053DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
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
        $this->addSql('ALTER TABLE match_game_bill ADD CONSTRAINT FK_88853964217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE match_game_bill ADD CONSTRAINT FK_888539649329866A FOREIGN KEY (match_game_id) REFERENCES match_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_to_role ADD CONSTRAINT FK_E88A85AFA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_to_role ADD CONSTRAINT FK_E88A85AF8E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');

        $this->addSql('CREATE INDEX IDX_B8EE3872D9923C03 ON club (emblem_id)');
        $this->addSql('CREATE INDEX IDX_67CB3B053DA5256D ON game_type (image_id)');
        $this->addSql('CREATE INDEX IDX_424480FEA76ED395 ON match_game (user_id)');
        $this->addSql('CREATE INDEX IDX_424480FE9C4C13F6 ON match_game (home_team_id)');
        $this->addSql('CREATE INDEX IDX_424480FE45185D02 ON match_game (away_team_id)');
        $this->addSql('CREATE INDEX IDX_424480FE508EF3BC ON match_game (game_type_id)');
        $this->addSql('CREATE INDEX IDX_424480FE4A087CA2 ON match_game (referee_id)');
        $this->addSql('CREATE INDEX IDX_424480FE494C36CA ON match_game (first_assistant_referee_id)');
        $this->addSql('CREATE INDEX IDX_424480FE371C85E7 ON match_game (second_assistant_referee_id)');
        $this->addSql('CREATE INDEX IDX_424480FEFFFD7D98 ON match_game (fourth_official_id)');
        $this->addSql('CREATE INDEX IDX_424480FE516E156B ON match_game (referee_observer_id)');
        $this->addSql('CREATE INDEX IDX_424480FE8A0BB485 ON match_game (delegate_id)');
        $this->addSql('CREATE INDEX IDX_88853964217BBB47 ON match_game_bill (person_id)');
        $this->addSql('CREATE INDEX IDX_888539649329866A ON match_game_bill (match_game_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F61190A32 ON team (club_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649217BBB47 ON user (person_id)');
        $this->addSql('CREATE INDEX IDX_E88A85AFA76ED395 ON user_to_role (user_id)');
        $this->addSql('CREATE INDEX IDX_E88A85AF8E0E3CA6 ON user_to_role (user_role_id)');
    }

    public function preDown(Schema $schema): void
    {
        $this->addSql('ALTER TABLE club CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE emblem_id emblem_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE game_type CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE image_id image_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE image CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE match_game CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_id user_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE home_team_id home_team_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE away_team_id away_team_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE game_type_id game_type_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE referee_id referee_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE first_assistant_referee_id first_assistant_referee_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE second_assistant_referee_id second_assistant_referee_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE fourth_official_id fourth_official_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE referee_observer_id referee_observer_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE delegate_id delegate_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE match_game_bill CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE person_id person_guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE match_game_id match_game_guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE person CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE team CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE club_id club_guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE person_id person_guid CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user_to_role CHANGE user_id user_guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE user_role_id user_role_guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE user_role CHANGE id guid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');

        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE3872D9923C03');
        $this->addSql('ALTER TABLE game_type DROP FOREIGN KEY FK_67CB3B053DA5256D');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FEA76ED395');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE9C4C13F6');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE45185D02');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE508EF3BC');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE4A087CA2');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE494C36CA');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE371C85E7');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FEFFFD7D98');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE516E156B');
        $this->addSql('ALTER TABLE match_game DROP FOREIGN KEY FK_424480FE8A0BB485');
        $this->addSql('ALTER TABLE match_game_bill DROP FOREIGN KEY FK_88853964217BBB47');
        $this->addSql('ALTER TABLE match_game_bill DROP FOREIGN KEY FK_888539649329866A');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F61190A32');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649217BBB47');
        $this->addSql('ALTER TABLE user_to_role DROP FOREIGN KEY FK_E88A85AFA76ED395');
        $this->addSql('ALTER TABLE user_to_role DROP FOREIGN KEY FK_E88A85AF8E0E3CA6');

        $this->addSql('DROP INDEX IDX_B8EE3872D9923C03 ON club');
        $this->addSql('DROP INDEX IDX_67CB3B053DA5256D ON game_type');
        $this->addSql('DROP INDEX IDX_424480FEA76ED395 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE9C4C13F6 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE45185D02 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE508EF3BC ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE4A087CA2 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE494C36CA ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE371C85E7 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FEFFFD7D98 ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE516E156B ON match_game');
        $this->addSql('DROP INDEX IDX_424480FE8A0BB485 ON match_game');
        $this->addSql('DROP INDEX IDX_88853964217BBB47 ON match_game_bill');
        $this->addSql('DROP INDEX IDX_888539649329866A ON match_game_bill');
        $this->addSql('DROP INDEX IDX_C4E0A61F61190A32 ON team');
        $this->addSql('DROP INDEX UNIQ_8D93D649217BBB47 ON user');
        $this->addSql('DROP INDEX IDX_E88A85AFA76ED395 ON user_to_role');
        $this->addSql('DROP INDEX IDX_E88A85AF8E0E3CA6 ON user_to_role');

        $this->addSql('ALTER TABLE club DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE game_type DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE image DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE match_game DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE match_game_bill DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE person DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE team DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_role DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE user_to_role DROP PRIMARY KEY');

        $this->addSql('ALTER TABLE club ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD emblem_id INT DEFAULT NULL AFTER emblem_guid, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE game_type ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD image_id INT DEFAULT NULL AFTER image_guid, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE image ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE match_game ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD user_id INT DEFAULT NULL AFTER user_guid, ADD home_team_id INT DEFAULT NULL AFTER home_team_guid, ADD away_team_id INT DEFAULT NULL AFTER away_team_guid, ADD game_type_id INT DEFAULT NULL AFTER game_type_guid, ADD referee_id INT DEFAULT NULL AFTER referee_guid, ADD first_assistant_referee_id INT DEFAULT NULL AFTER first_assistant_referee_guid, ADD second_assistant_referee_id INT DEFAULT NULL AFTER second_assistant_referee_guid, ADD fourth_official_id INT DEFAULT NULL AFTER fourth_official_guid, ADD referee_observer_id INT DEFAULT NULL AFTER referee_observer_guid, ADD delegate_id INT DEFAULT NULL AFTER delegate_guid, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE match_game_bill ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD person_id INT DEFAULT NULL AFTER person_guid, ADD match_game_id INT DEFAULT NULL AFTER match_game_guid, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE person ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE team ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD club_id INT DEFAULT NULL AFTER club_guid, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE user ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD person_id INT DEFAULT NULL AFTER person_guid, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE user_role ADD id INT AUTO_INCREMENT NOT NULL FIRST, ADD PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE user_to_role ADD user_id INT DEFAULT NULL AFTER user_guid, ADD user_role_id INT DEFAULT NULL AFTER user_role_guid');

        $this->addSql('UPDATE club c SET c.emblem_id = (SELECT i.id FROM image i WHERE i.guid = c.emblem_guid)');
        $this->addSql('UPDATE game_type gt SET gt.image_id = (SELECT i.id FROM image i WHERE i.guid = gt.image_guid)');
        $this->addSql('UPDATE match_game mg SET
            mg.user_id = (SELECT u.id FROM user u WHERE u.guid = mg.user_guid),
            mg.home_team_id = (SELECT t.id FROM team t WHERE t.guid = mg.home_team_guid),
            mg.away_team_id = (SELECT t.id FROM team t WHERE t.guid = mg.away_team_guid),
            mg.game_type_id = (SELECT gt.id FROM game_type gt WHERE gt.guid = mg.game_type_guid),
            mg.referee_id = (SELECT p.id FROM person p WHERE p.guid = mg.referee_guid),
            mg.first_assistant_referee_id = (SELECT p.id FROM person p WHERE p.guid = mg.first_assistant_referee_guid),
            mg.second_assistant_referee_id = (SELECT p.id FROM person p WHERE p.guid = mg.second_assistant_referee_guid),
            mg.fourth_official_id = (SELECT p.id FROM person p WHERE p.guid = mg.fourth_official_guid),
            mg.referee_observer_id = (SELECT p.id FROM person p WHERE p.guid = mg.referee_observer_guid),
            mg.delegate_id = (SELECT p.id FROM person p WHERE p.guid = mg.delegate_guid)
        ');
        $this->addSql('UPDATE match_game_bill mgb SET mgb.person_id = (SELECT p.id FROM person p WHERE p.guid = mgb.person_guid), mgb.match_game_id = (SELECT mg.id FROM match_game mg WHERE mg.guid = mgb.match_game_guid)');
        $this->addSql('UPDATE team t SET t.club_id = (SELECT c.id FROM club c WHERE c.guid = t.club_guid)');
        $this->addSql('UPDATE user u SET u.person_id = (SELECT p.id FROM person p WHERE p.guid = u.person_guid)');
        $this->addSql('UPDATE user_to_role utr SET utr.user_id = (SELECT u.id FROM user u WHERE u.guid = utr.user_guid), utr.user_role_id = (SELECT ur.id FROM user_role ur WHERE ur.guid = utr.user_role_guid)');

        $this->addSql('ALTER TABLE user_to_role ADD PRIMARY KEY (user_id, user_role_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE club DROP guid, DROP emblem_guid');
        $this->addSql('ALTER TABLE game_type DROP guid, DROP image_guid');
        $this->addSql('ALTER TABLE image DROP guid');
        $this->addSql('ALTER TABLE match_game DROP guid, DROP user_guid, DROP home_team_guid, DROP away_team_guid, DROP game_type_guid, DROP referee_guid, DROP first_assistant_referee_guid, DROP second_assistant_referee_guid, DROP fourth_official_guid, DROP referee_observer_guid, DROP delegate_guid');
        $this->addSql('ALTER TABLE match_game_bill DROP guid, DROP person_guid, DROP match_game_guid');
        $this->addSql('ALTER TABLE person DROP guid');
        $this->addSql('ALTER TABLE team DROP guid, DROP club_guid');
        $this->addSql('ALTER TABLE user DROP guid, DROP person_guid');
        $this->addSql('ALTER TABLE user_to_role DROP user_guid, DROP user_role_guid');
        $this->addSql('ALTER TABLE user_role DROP guid');

        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE3872D9923C03 FOREIGN KEY (emblem_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE game_type ADD CONSTRAINT FK_67CB3B053DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
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
        $this->addSql('ALTER TABLE match_game_bill ADD CONSTRAINT FK_88853964217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE match_game_bill ADD CONSTRAINT FK_888539649329866A FOREIGN KEY (match_game_id) REFERENCES match_game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_to_role ADD CONSTRAINT FK_E88A85AFA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_to_role ADD CONSTRAINT FK_E88A85AF8E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id) ON DELETE CASCADE');

        $this->addSql('CREATE INDEX IDX_B8EE3872D9923C03 ON club (emblem_id)');
        $this->addSql('CREATE INDEX IDX_67CB3B053DA5256D ON game_type (image_id)');
        $this->addSql('CREATE INDEX IDX_424480FEA76ED395 ON match_game (user_id)');
        $this->addSql('CREATE INDEX IDX_424480FE9C4C13F6 ON match_game (home_team_id)');
        $this->addSql('CREATE INDEX IDX_424480FE45185D02 ON match_game (away_team_id)');
        $this->addSql('CREATE INDEX IDX_424480FE508EF3BC ON match_game (game_type_id)');
        $this->addSql('CREATE INDEX IDX_424480FE4A087CA2 ON match_game (referee_id)');
        $this->addSql('CREATE INDEX IDX_424480FE494C36CA ON match_game (first_assistant_referee_id)');
        $this->addSql('CREATE INDEX IDX_424480FE371C85E7 ON match_game (second_assistant_referee_id)');
        $this->addSql('CREATE INDEX IDX_424480FEFFFD7D98 ON match_game (fourth_official_id)');
        $this->addSql('CREATE INDEX IDX_424480FE516E156B ON match_game (referee_observer_id)');
        $this->addSql('CREATE INDEX IDX_424480FE8A0BB485 ON match_game (delegate_id)');
        $this->addSql('CREATE INDEX IDX_88853964217BBB47 ON match_game_bill (person_id)');
        $this->addSql('CREATE INDEX IDX_888539649329866A ON match_game_bill (match_game_id)');
        $this->addSql('CREATE INDEX IDX_C4E0A61F61190A32 ON team (club_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649217BBB47 ON user (person_id)');
        $this->addSql('CREATE INDEX IDX_E88A85AFA76ED395 ON user_to_role (user_id)');
        $this->addSql('CREATE INDEX IDX_E88A85AF8E0E3CA6 ON user_to_role (user_role_id)');
    }
}
