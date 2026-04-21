<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\HIUPD;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\Segment\BaseSegment;

/**
 * Segment: Kontoinformation (Version 4)
 * Bezugssegment: HKVVB
 * Sender: Kreditinstitut
 *
 * Note: This is a repeated segment, there is one instance per account.
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/archiv/HBCI_V2.x_FV.zip
 * File: HBCI22 Final.pdf
 * Section: V.3 "Kontoinformation"
 */
class HIUPDv4 extends BaseSegment implements HIUPD
{
    public \BytesCommerce\Segment\Common\KtvV3 $kontoverbindung;

    public string $kundenId;

    public ?string $kontowaehrung = null;

    public string $name1;

    public ?string $name2 = null;

    public ?string $kontoproduktbezeichnung = null;

    public ?KontolimitV1 $kontolimit = null;

    /** @var ErlaubteGeschaeftsvorfaelleV1[]|null @Max(98) */
    public ?array $erlaubteGeschaeftsvorfaelle = null;

    public function matchesAccount(SEPAAccount $sepaAccount): bool
    {
        return !is_null($this->kontoverbindung->kontonummer)
            && $this->kontoverbindung->kontonummer == $sepaAccount->getAccountNumber();
    }

    public function getErlaubteGeschaeftsvorfaelle(): array
    {
        return $this->erlaubteGeschaeftsvorfaelle ?? [];
    }
}
