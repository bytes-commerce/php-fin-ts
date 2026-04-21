<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\Common;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\Segment\BaseDeg;

/**
 * Data Element Group: Kontoverbindung international (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: B.3.2
 */
class Kti extends BaseDeg implements AccountInfo
{
    /** Max length: 34 */
    public ?string $iban = null;

    /** Max length: 11, required if IBAN is present. */
    public ?string $bic = null;

    // The following fields can only be set if the BPD parameters allow it. If they are set, the fields above become
    // optional.
    /** Also known as Depotnummer. */
    public ?string $kontonummer = null;

    public ?string $unterkontomerkmal = null;

    public ?Kik $kreditinstitutskennung = null;

    public function validate(): void
    {
        parent::validate();
        if ($this->iban !== null) {
            if ($this->bic == null) {
                throw new \InvalidArgumentException('Kti cannot have IBAN without BIC');
            }
        } elseif ($this->kontonummer === null || !$this->kreditinstitutskennung instanceof \BytesCommerce\Segment\Common\Kik) {
            throw new \InvalidArgumentException('Kti must have IBAN+BIC or Kontonummer+Kik or both');
        }
    }

    public static function create(?string $iban, ?string $bic): Kti
    {
        $kti = new Kti();
        $kti->iban = $iban;
        $kti->bic = $bic;
        return $kti;
    }

    public static function fromAccount(SEPAAccount $sepaAccount): Kti
    {
        $kti = static::create($sepaAccount->getIban(), $sepaAccount->getBic());
        $kti->kontonummer = $sepaAccount->getAccountNumber();
        $kti->unterkontomerkmal = $sepaAccount->getSubAccount();
        $kti->kreditinstitutskennung = Kik::create($sepaAccount->getBlz());
        return $kti;
    }

    public function getAccountNumber(): string
    {
        return $this->iban ?? $this->kontonummer;
    }

    public function getBankIdentifier(): ?string
    {
        return $this->bic ?? $this->kreditinstitutskennung->kreditinstitutscode;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function getKontonummer(): ?string
    {
        return $this->kontonummer;
    }

    public function getUnterkontomerkmal(): ?string
    {
        return $this->unterkontomerkmal;
    }

    public function getKreditinstitutskennung(): ?Kik
    {
        return $this->kreditinstitutskennung;
    }
}
