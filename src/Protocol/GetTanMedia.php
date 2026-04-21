<?php

declare(strict_types=1);



namespace BytesCommerce\Protocol;

use BytesCommerce\BaseAction;
use BytesCommerce\Segment\TAB\HITAB;
use BytesCommerce\Segment\TAB\HKTABv4;
use BytesCommerce\Segment\TAB\HKTABv5;
use BytesCommerce\Segment\TAB\TanMediumListe;
use BytesCommerce\UnsupportedException;

/**
 * Fetches the TAN media (e.g. different mobile phones or iTAN lists) that are available to the user (HTKAB).
 */
class GetTanMedia extends BaseAction
{
    /** @var TanMediumListe[]|null */
    private $tanMedia;

    protected function createRequest(BPD $bpd, ?UPD $upd): \BytesCommerce\Segment\TAB\HKTABv4|\BytesCommerce\Segment\TAB\HKTABv5
    {
        // Prepare the HKTAB request.
        $baseSegment = $bpd->requireLatestSupportedParameters('HITABS');
        return match ($baseSegment->getVersion()) {
            4 => HKTABv4::createEmpty(),
            5 => HKTABv5::createEmpty(),
            default => throw new UnsupportedException('Unsupported HKTAB version: ' . $baseSegment->getVersion()),
        };
    }

    public function processResponse(Message $message): void
    {
        parent::processResponse($message);
        /** @var HITAB $baseSegment */
        $baseSegment = $message->requireSegment(HITAB::class);
        $this->tanMedia = $baseSegment->getTanMediumListe() ?? [];
    }

    /**
     * @return TanMediumListe[]|null
     */
    public function getTanMedia(): ?array
    {
        $this->ensureDone();
        return $this->tanMedia;
    }
}
