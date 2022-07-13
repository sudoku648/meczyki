<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220713121033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix voivodeships for enums.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE person SET address_voivodeship=\'lower_silesian\' WHERE address_voivodeship=\'dolnośląskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'kuyavian-pomeranian\' WHERE address_voivodeship=\'kujawsko-pomorskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'lublin\' WHERE address_voivodeship=\'lubelskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'lubusz\' WHERE address_voivodeship=\'lubuskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'lodz\' WHERE address_voivodeship=\'łódzkie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'lesser_poland\' WHERE address_voivodeship=\'małopolskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'masovian\' WHERE address_voivodeship=\'mazowieckie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'opole\' WHERE address_voivodeship=\'opolskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'subcarpathian\' WHERE address_voivodeship=\'podkarpackie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'podlaskie\' WHERE address_voivodeship=\'podlaskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'pomeranian\' WHERE address_voivodeship=\'pomorskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'silesian\' WHERE address_voivodeship=\'śląskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'holy_cross\' WHERE address_voivodeship=\'świętokrzyskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'warmian-masurian\' WHERE address_voivodeship=\'warmińsko-mazurskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'greater_poland\' WHERE address_voivodeship=\'wielkopolskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'west_pomeranian\' WHERE address_voivodeship=\'zachodniopomorskie\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE person SET address_voivodeship=\'dolnośląskie\' WHERE address_voivodeship=\'lower_silesian\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'kujawsko-pomorskie\' WHERE address_voivodeship=\'kuyavian-pomeranian\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'lubelskie\' WHERE address_voivodeship=\'lublin\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'lubuskie\' WHERE address_voivodeship=\'lubusz\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'łódzkie\' WHERE address_voivodeship=\'lodz\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'małopolskie\' WHERE address_voivodeship=\'lesser_poland\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'mazowieckie\' WHERE address_voivodeship=\'masovian\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'opolskie\' WHERE address_voivodeship=\'opole\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'podkarpackie\' WHERE address_voivodeship=\'subcarpathian\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'podlaskie\' WHERE address_voivodeship=\'podlaskie\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'pomorskie\' WHERE address_voivodeship=\'pomeranian\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'śląskie\' WHERE address_voivodeship=\'silesian\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'świętokrzyskie\' WHERE address_voivodeship=\'holy_cross\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'warmińsko-mazurskie\' WHERE address_voivodeship=\'warmian-masurian\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'wielkopolskie\' WHERE address_voivodeship=\'greater_poland\'');
        $this->addSql('UPDATE person SET address_voivodeship=\'zachodniopomorskie\' WHERE address_voivodeship=\'west_pomeranian\'');
    }
}
