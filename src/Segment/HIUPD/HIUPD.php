<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\HIUPD;

use BytesCommerce\Model\SEPAAccount;

/**
 * Segment: Kontoinformation
 * Bezugssegment: HKVVB
 * Sender: Kreditinstitut
 */
interface HIUPD
{
    /**
     * @param SEPAAccount $sepaAccount An account.
     * @return bool True if this HIUPD segment pertains to the given account.
     */
    public function matchesAccount(SEPAAccount $sepaAccount): bool;

    /**
     * @return ErlaubteGeschaeftsvorfaelle[]
     */
    public function getErlaubteGeschaeftsvorfaelle(): array;
}
