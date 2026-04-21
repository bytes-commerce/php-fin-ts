<?php

declare(strict_types=1);



namespace BytesCommerce\Action;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\PaginateableAction;
use BytesCommerce\Protocol\BPD;
use BytesCommerce\Protocol\Message;
use BytesCommerce\Protocol\UnexpectedResponseException;
use BytesCommerce\Protocol\UPD;
use BytesCommerce\Segment\Common\Kti;
use BytesCommerce\Segment\Common\Kto;
use BytesCommerce\Segment\Common\KtvV3;
use BytesCommerce\Segment\SAL\HISAL;
use BytesCommerce\Segment\SAL\HKSALv4;
use BytesCommerce\Segment\SAL\HKSALv5;
use BytesCommerce\Segment\SAL\HKSALv6;
use BytesCommerce\Segment\SAL\HKSALv7;
use BytesCommerce\UnsupportedException;

/**
 * Runs an HKSAL request the current balance of the given account.
 */
class GetBalance extends PaginateableAction
{
    // Request (if you add a field here, update __serialize() and __unserialize() as well).
    private ?SEPAAccount $sepaAccount = null;

    private ?bool $allAccounts = null;

    // Response
    /** @var HISAL[] */
    private array $response = [];

    /**
     * @param SEPAAccount $sepaAccount The account to get the balance for. This can be constructed based on information
     *     that the user entered, or it can be {@link SEPAAccount} instance retrieved from {@link GetSEPAAccounts}.
     * @param bool $allAccounts If set to true, will return balances for all accounts of the user. You still need to
     *     pass one of the accounts into $account, though.
     */
    public static function create(SEPAAccount $sepaAccount, bool $allAccounts = false): GetBalance
    {
        $getBalance = new GetBalance();
        $getBalance->sepaAccount = $sepaAccount;
        $getBalance->allAccounts = $allAccounts;
        return $getBalance;
    }

    /**
     * @deprecated Beginning from PHP7.4 __unserialize is used for new generated strings, then this method is only used for previously generated strings - remove after May 2023
     */
    public function serialize(): string
    {
        return serialize($this->__serialize());
    }

    public function __serialize(): array
    {
        return [
            parent::__serialize(),
            $this->sepaAccount, $this->allAccounts,
        ];
    }

    /**
     * @deprecated Beginning from PHP7.4 __unserialize is used for new generated strings, then this method is only used for previously generated strings - remove after May 2023
     *
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        self::__unserialize(unserialize($serialized));
    }

    public function __unserialize(array $serialized): void
    {
        list(
            $parentSerialized,
            $this->sepaAccount, $this->allAccounts,
        ) = $serialized;

        is_array($parentSerialized) ?
            parent::__unserialize($parentSerialized) :
            parent::unserialize($parentSerialized);
    }

    /**
     * @return HISAL[]
     */
    public function getBalances()
    {
        $this->ensureDone();
        return $this->response;
    }

    protected function createRequest(BPD $bpd, ?UPD $upd): HKSALv4|HKSALv5|HKSALv6|HKSALv7
    {
        $baseSegment = $bpd->requireLatestSupportedParameters('HISALS');
        return match ($baseSegment->getVersion()) {
            4 => HKSALv4::create(Kto::fromAccount($this->sepaAccount)),
            5 => HKSALv5::create(KtvV3::fromAccount($this->sepaAccount), $this->allAccounts),
            6 => HKSALv6::create(KtvV3::fromAccount($this->sepaAccount), $this->allAccounts),
            7 => HKSALv7::create(Kti::fromAccount($this->sepaAccount), $this->allAccounts),
            default => throw new UnsupportedException('Unsupported HKSAL version: ' . $baseSegment->getVersion()),
        };
    }

    public function processResponse(Message $message): void
    {
        parent::processResponse($message);

        $responseSegments = $message->findSegments(HISAL::class);
        if ($responseSegments === []) {
            throw new UnexpectedResponseException('No HISAL segments received!');
        }

        $this->response = array_merge($this->response, $responseSegments);
    }
}
