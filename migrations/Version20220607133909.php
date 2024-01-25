<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220607133909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'MatchGameBill entity';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE match_game_bill (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, match_game_id INT NOT NULL, base_equivalent INT NOT NULL, percent_of_base_equivalent INT NOT NULL, gross_equivalent INT NOT NULL, tax_deductible_stake_percent INT NOT NULL, tax_deductible_expenses INT NOT NULL, taxation_base INT NOT NULL, income_tax_stake_percent INT NOT NULL, income_tax INT NOT NULL, equivalent_to_withdraw INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', INDEX IDX_88853964217BBB47 (person_id), INDEX IDX_888539649329866A (match_game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE match_game_bill ADD CONSTRAINT FK_88853964217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE match_game_bill ADD CONSTRAINT FK_888539649329866A FOREIGN KEY (match_game_id) REFERENCES match_game (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE match_game_bill');
    }
}
