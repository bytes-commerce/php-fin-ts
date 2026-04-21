<?php

declare(strict_types=1);



namespace BytesCommerce\Protocol;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\Segment\HIUPA\HIUPAv4;
use BytesCommerce\Segment\HIUPD\HIUPD;

/**
 * Contains the "Userparameterdaten" (UPD), i.e. configuration information that was retrieved from the bank server
 * during dialog initialization and that is user-specific. This library currently does not store persisted UPD, so it
 * just retrieves them freshly every time.
 */
class UPD
{
    /** @var HIUPAv4 The HIBPA segment received from the server, which contains most of the UPD data. */
    public $hiupa;

    /** @var HIUPD[] All HIUPD segments from the server, which contain *per-account* information. */
    public $hiupd;

    public function getVersion()
    {
        return $this->hiupa->updVersion;
    }

    /**
     * @param Message $message A dialog initialization response from the server.
     * @return bool True if the UPD data is contained in the response and {@link extractFromResponse()} would
     *     succeed.
     */
    public static function containedInResponse(Message $message): bool
    {
        return $message->hasSegment(HIUPAv4::class);
    }

    /**
     * @param Message $message The dialog initialization response from the server, which should contain the UPD
     *     data.
     * @return UPD A new UPD instance with the extracted configuration data.
     */
    public static function extractFromResponse(Message $message): UPD
    {
        $upd = new UPD();
        $upd->hiupa = $message->requireSegment(HIUPAv4::class);
        $upd->hiupd = $message->findSegments(HIUPD::class);
        return $upd;
    }

    /**
     * @param SEPAAccount $sepaAccount An account.
     * @return HIUPD|null The HIUPD segment for this account, or null if none exists for this account.
     */
    public function findHiupd(SEPAAccount $sepaAccount): ?HIUPD
    {
        foreach ($this->hiupd as $hiupd) {
            if ($hiupd->matchesAccount($sepaAccount)) {
                return $hiupd;
            }
        }

        return null;
    }

    /**
     * @param SEPAAccount $sepaAccount The account to test the support for
     * @param string $requestName The request that shall be sent to the bank.
     * @return bool True if the given request can be used by the current user for the given account.
     */
    public function isRequestSupportedForAccount(SEPAAccount $sepaAccount, string $requestName): bool
    {
        $hiupd = $this->findHiupd($sepaAccount);
        if ($hiupd instanceof \BytesCommerce\Segment\HIUPD\HIUPD) {
            foreach ($hiupd->getErlaubteGeschaeftsvorfaelle() as $erlaubteGeschaeftsvorfaelle) {
                if ($erlaubteGeschaeftsvorfaelle->getGeschaeftsvorfall() == $requestName) {
                    return true;
                }
            }
        }

        return false;
    }
}
