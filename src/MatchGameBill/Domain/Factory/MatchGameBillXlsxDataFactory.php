<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Factory;

use NumberFormatter;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\MatchGameBillXlsxData;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part\CalculationsData;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part\MatchGameData;
use Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part\PersonData;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class MatchGameBillXlsxDataFactory
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function create(MatchGameBill $matchGameBill): MatchGameBillXlsxData
    {
        $matchGame = $matchGameBill->getMatchGame();
        $person    = $matchGameBill->getPerson();

        return new MatchGameBillXlsxData(
            matchGame: new MatchGameData(
                date: $matchGame->getDateTime()->format('d.m.Y'),
                venue: $matchGame->getVenue()->getValue(),
                gameTypeName: $matchGame->getGameType()?->getName() ?? '',
                homeTeamName: $matchGame->getHomeTeam()?->getName() ?? '',
                awayTeamName: $matchGame->getAwayTeam()?->getName() ?? '',
            ),
            person: new PersonData(
                function: $this->translator->trans(
                    id: $matchGameBill->getFunction()->value,
                    domain: 'Person',
                ),
                firstName: $person->getFirstName()->getValue(),
                lastName: $person->getLastName()->getValue(),
                dateOfBirth: $person->getDateOfBirth()?->format('d.m.Y') ?? '',
                placeOfBirth: $person->getPlaceOfBirth() ?? '',
                address: $person->getAddress()->formatted(),
                voivodeship: $person->getAddress()->getVoivodeship()
                    ? $this->translator->trans(
                        id: $person->getAddress()->getVoivodeship()->getName(),
                        domain: 'Voivodeship',
                        locale: 'pl',
                    )
                    : '',
                county: $person->getAddress()->getCounty() ?? '',
                gmina: $person->getAddress()->getGmina() ?? '',
                taxOffice: $person->getTaxOfficeName() && $person->getTaxOfficeAddress()
                    ? $person->getTaxOfficeName() . ', ' . $person->getTaxOfficeAddress()
                    : '',
                isPesel: null !== $person->getPesel(),
                peselOrNip: $person->getPesel()?->getValue() ?? $person->getNip()?->getValue() ?? '',
            ),
            calculations: new CalculationsData(
                baseEquivalent: $matchGameBill->getBaseEquivalent()->getAmount(),
                percentOfBaseEquivalent: $matchGameBill->getPercentOfBaseEquivalent()->getValue(),
                grossEquivalent: $matchGameBill->getGrossEquivalent()->getAmount(),
                taxDeductibleExpenses: $matchGameBill->getTaxDeductibleExpenses()->getAmount(),
                taxationBase: $matchGameBill->getTaxationBase()->getAmount(),
                incomeTax: $matchGameBill->getIncomeTax()->getAmount(),
                equivalentToWithdraw: $matchGameBill->getEquivalentToWithdraw()->getAmount(),
                amountInWords: $this->getAmountInWords($matchGameBill->getEquivalentToWithdraw()->getAmount()),
            ),
            iban: $person->getIban()?->getNumber() ?? '',
            email: $person->getEmail() ?? '',
            allowsToSendPitByEmail: $person->allowsToSendPitByEmail(),
        );
    }

    private function getAmountInWords(int $amount): string
    {
        $numberFormatter = new NumberFormatter('pl', NumberFormatter::SPELLOUT);

        return $numberFormatter->format($amount) . ' zł 00/100';
    }
}
