<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240229175029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make person functions as json';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE match_game_bill ADD `function` VARCHAR(20) DEFAULT NULL AFTER match_game_id');
        $this->addSql('ALTER TABLE person ADD functions JSON NOT NULL AFTER mobile_phone');
        $this->addSql('UPDATE person SET functions = \'[]\'');
        $this->addSql('UPDATE person
            SET functions = JSON_ARRAY_APPEND(functions, \'$\', \'delegate\')
            WHERE is_delegate = 1
        ');
        $this->addSql('UPDATE person
            SET functions = JSON_ARRAY_APPEND(functions, \'$\', \'referee\')
            WHERE is_referee = 1
        ');
        $this->addSql('UPDATE person
            SET functions = JSON_ARRAY_APPEND(functions, \'$\', \'referee_observer\')
            WHERE is_referee_observer = 1
        ');
        $this->addSql('ALTER TABLE person DROP is_delegate, DROP is_referee, DROP is_referee_observer');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE match_game_bill DROP function');
        $this->addSql('ALTER TABLE person
            ADD is_delegate TINYINT(1) NOT NULL DEFAULT 0 AFTER mobile_phone,
            ADD is_referee TINYINT(1) NOT NULL DEFAULT 0 AFTER is_delegate,
            ADD is_referee_observer TINYINT(1) NOT NULL DEFAULT 0 AFTER is_referee
        ');
        $this->addSql('UPDATE person SET is_delegate = JSON_CONTAINS(functions, \'"delegate"\')');
        $this->addSql('UPDATE person SET is_referee = JSON_CONTAINS(functions, \'"referee"\')');
        $this->addSql('UPDATE person SET is_referee_observer = JSON_CONTAINS(functions, \'"referee_observer"\')');
        $this->addSql('ALTER TABLE person DROP functions');
    }
}
