<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

namespace BytesCommerce\Segment\Common;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\Segment\BaseDeg;

/**
 * Data Element Group: Kontoverbindung
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: Gesamtdok_HBCI210.pdf
 * Section: II.5.3.3
 */
class Kto extends BaseDeg implements AccountInfo
{
    public string $kontonummer;
     // Aka Depotnummer
    public Kik $kik;

    public static function create(string $kontonummer, Kik $kik): Kto
    {
        $kto = new Kto();
        $kto->kontonummer = $kontonummer;
        $kto->kik = $kik;
        return $kto;
    }

    public static function fromAccount(SEPAAccount $sepaAccount): Kto
    {
        return static::create($sepaAccount->getAccountNumber(), Kik::create($sepaAccount->getBlz()));
    }

    public function getAccountNumber(): string
    {
        return $this->kontonummer;
    }

    public function getBankIdentifier(): ?string
    {
        return $this->kik->kreditinstitutscode;
    }

    public function getKontonummer(): string
    {
        return $this->kontonummer;
    }

    public function getKik(): Kik
    {
        return $this->kik;
    }
}
