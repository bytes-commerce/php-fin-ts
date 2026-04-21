<?php

declare(strict_types=1);



namespace BytesCommerce\Action;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\PaginateableAction;
use BytesCommerce\Protocol\BPD;
use BytesCommerce\Protocol\Message;
use BytesCommerce\Protocol\UPD;
use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Common\Ktz;
use BytesCommerce\Segment\HIRMS\Rueckmeldungscode;
use BytesCommerce\Segment\SPA\HISPA;
use BytesCommerce\Segment\SPA\HKSPAv1;
use BytesCommerce\Segment\SPA\HKSPAv2;
use BytesCommerce\Segment\SPA\HKSPAv3;
use BytesCommerce\UnsupportedException;

/**
 * Runs an HKSPA request to retrieve account details about the accounts that the user can access through FinTs.
 *
 * TODO In future, once all banks populate the BIC in HIUPD.erweiterungKontobezogen, or if we force library users to
 * supply the BIC to us, we won't need to send an HKSPA anymore, but we can simply fulfil this action from the UPD.
 */
class GetSEPAAccounts extends PaginateableAction
{
    // Empty request, in order to retrieve all accounts.

    // Response
    /** @var SEPAAccount[] */
    private ?array $accounts = null;

    /**
     * @return GetSEPAAccounts A new action instance.
     */
    public static function create(): GetSEPAAccounts
    {
        return new GetSEPAAccounts();
    }

    /**
     * @return SEPAAccount[]
     */
    public function getAccounts(): array
    {
        $this->ensureDone();
        return $this->accounts;
    }

    protected function createRequest(BPD $bpd, ?UPD $upd): \BytesCommerce\Segment\SPA\HKSPAv1|\BytesCommerce\Segment\SPA\HKSPAv2
    {
        $baseSegment = $bpd->requireLatestSupportedParameters('HISPAS');
        return match ($baseSegment->getVersion()) {
            1 => HKSPAv1::createEmpty(),
            2 => HKSPAv2::createEmpty(),
            3 => HKSPAv3::createEmpty(),
            default => throw new UnsupportedException('Unsupported HKSPA version: ' . $baseSegment->getVersion()),
        };
    }

    public function processResponse(Message $message): void
    {
        parent::processResponse($message);

        // Banks send just 3010 and no HISPA in case there are no accounts (or at least none that the bank is able to
        // report through HISPA).
        if ($message->findRueckmeldung(Rueckmeldungscode::NICHT_VERFUEGBAR) instanceof \BytesCommerce\Segment\HIRMS\Rueckmeldung) {
            $this->accounts = [];
            return;
        }

        /** @var HISPA $baseSegment */
        $baseSegment = $message->requireSegment(HISPA::class);
        $this->accounts = array_map(function (\BytesCommerce\Segment\Common\Ktz $ktz): \BytesCommerce\Model\SEPAAccount {
            /** @var Ktz $ktz */
            $sepaAccount = new SEPAAccount();
            $sepaAccount->setIban($ktz->iban);
            $sepaAccount->setBic($ktz->bic);
            $sepaAccount->setAccountNumber($ktz->kontonummer);
            $sepaAccount->setSubAccount($ktz->unterkontomerkmal);
            $sepaAccount->setBlz($ktz->kreditinstitutskennung->kreditinstitutscode);
            return $sepaAccount;
        }, $baseSegment->getSepaKontoverbindung());
    }
}
