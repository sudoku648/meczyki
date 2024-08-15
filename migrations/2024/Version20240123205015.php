<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240123205015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Changing entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE match_game_bill
            CHANGE base_equivalent base_equivalent_amount INT NOT NULL,
            CHANGE gross_equivalent gross_equivalent_amount INT NOT NULL,
            CHANGE tax_deductible_expenses tax_deductible_expenses_amount INT NOT NULL,
            CHANGE taxation_base taxation_base_amount INT NOT NULL,
            CHANGE income_tax income_tax_amount INT NOT NULL,
            CHANGE equivalent_to_withdraw equivalent_to_withdraw_amount INT NOT NULL,
            ADD base_equivalent_currency VARCHAR(255) DEFAULT NULL AFTER base_equivalent_amount,
            ADD gross_equivalent_currency VARCHAR(255) DEFAULT NULL AFTER gross_equivalent_amount,
            ADD tax_deductible_expenses_currency VARCHAR(255) DEFAULT NULL AFTER tax_deductible_expenses_amount,
            ADD taxation_base_currency VARCHAR(255) DEFAULT NULL AFTER taxation_base_amount,
            ADD income_tax_currency VARCHAR(255) DEFAULT NULL AFTER income_tax_amount,
            ADD equivalent_to_withdraw_currency VARCHAR(255) DEFAULT NULL AFTER equivalent_to_withdraw_amount
        ');

        $this->addSql('UPDATE match_game_bill SET
            base_equivalent_currency = \'PLN\',
            gross_equivalent_currency = \'PLN\',
            tax_deductible_expenses_currency = \'PLN\',
            taxation_base_currency = \'PLN\',
            income_tax_currency = \'PLN\',
            equivalent_to_withdraw_currency = \'PLN\'
        ');

        $this->addSql('ALTER TABLE match_game_bill
            CHANGE base_equivalent_currency base_equivalent_currency VARCHAR(255) NOT NULL,
            CHANGE gross_equivalent_currency gross_equivalent_currency VARCHAR(255) NOT NULL,
            CHANGE tax_deductible_expenses_currency tax_deductible_expenses_currency VARCHAR(255) NOT NULL,
            CHANGE taxation_base_currency taxation_base_currency VARCHAR(255) NOT NULL,
            CHANGE income_tax_currency income_tax_currency VARCHAR(255) NOT NULL,
            CHANGE equivalent_to_withdraw_currency equivalent_to_withdraw_currency VARCHAR(255) NOT NULL
        ');

        $this->addSql(
            'UPDATE game_type SET
            `name` = CONCAT(`name`, \' Grupa \', `group`)
            WHERE `group` IS NOT NULL'
        );

        $this->addSql('ALTER TABLE game_type DROP `group`');

        $this->addSql('ALTER TABLE team CHANGE full_name `name` VARCHAR(200) NOT NULL');

        $this->addSql('ALTER TABLE club RENAME INDEX uniq_b8ee38725e237e06 TO UNIQ_B8EE3872999517A');
        $this->addSql('ALTER TABLE match_game CHANGE season season CHAR(9) DEFAULT NULL');
        $this->addSql(
            'ALTER TABLE person
            ADD iban_prefix VARCHAR(2) DEFAULT NULL AFTER iban,
            ADD iban_number VARCHAR(26) DEFAULT NULL AFTER iban_prefix,
            CHANGE mobile_phone mobile_phone CHAR(12) DEFAULT NULL,
            CHANGE address_zip_code address_post_code CHAR(6) DEFAULT NULL,
            CHANGE address_powiat address_county VARCHAR(100) DEFAULT NULL'
        );
        $this->addSql(
            'UPDATE person SET iban_prefix = LEFT(iban, 2), iban_number = SUBSTRING(iban, 3)
            WHERE iban IS NOT NULL'
        );
        $this->addSql('ALTER TABLE person DROP iban');
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE match_game_bill
            CHANGE base_equivalent_amount base_equivalent INT NOT NULL,
            CHANGE gross_equivalent_amount gross_equivalent INT NOT NULL,
            CHANGE tax_deductible_expenses_amount tax_deductible_expenses INT NOT NULL,
            CHANGE taxation_base_amount taxation_base INT NOT NULL,
            CHANGE income_tax_amount income_tax INT NOT NULL,
            CHANGE equivalent_to_withdraw_amount equivalent_to_withdraw INT NOT NULL,
            DROP base_equivalent_currency,
            DROP gross_equivalent_currency,
            DROP tax_deductible_expenses_currency,
            DROP taxation_base_currency,
            DROP income_tax_currency,
            DROP equivalent_to_withdraw_currency'
        );

        $this->addSql('ALTER TABLE game_type ADD `group` VARCHAR(150) DEFAULT NULL AFTER `name`');

        $this->addSql(
            'UPDATE game_type SET
            `name` = SUBSTRING_INDEX(CONCAT(`name`, \' Grupa \', `group`), \'Grupa\', 1),
            `group` = SUBSTRING_INDEX(CONCAT(`name`, \' Grupa \', `group`), \'Grupa\', -1)
            WHERE SUBSTRING_INDEX(CONCAT(`name`, \' Grupa \', `group`), \'Grupa\', 1) IS NOT NULL'
        );

        $this->addSql('ALTER TABLE team CHANGE `name` full_name VARCHAR(200) NOT NULL');

        $this->addSql('ALTER TABLE club RENAME INDEX uniq_b8ee3872999517a TO UNIQ_B8EE38725E237E06');
        $this->addSql('ALTER TABLE match_game CHANGE season season VARCHAR(10) DEFAULT NULL');
        $this->addSql(
            'ALTER TABLE person
            ADD iban VARCHAR(28) DEFAULT NULL AFTER nip,
            CHANGE mobile_phone mobile_phone VARCHAR(12) DEFAULT NULL,
            CHANGE address_post_code address_zip_code VARCHAR(6) DEFAULT NULL,
            CHANGE address_county address_powiat VARCHAR(100) DEFAULT NULL'
        );
        $this->addSql(
            'UPDATE person SET iban = CONCAT(iban_prefix, iban_number)
            WHERE iban_prefix IS NOT NULL
            AND iban_number IS NOT NULL'
        );
        $this->addSql('ALTER TABLE person DROP iban_prefix, DROP iban_number');
    }
}
