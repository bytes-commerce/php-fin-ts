<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\HIPINS;

use BytesCommerce\Segment\BaseDeg;

/**
 * Data Element Group: Geschäftsvorfallspezifische PIN/TAN-Informationen
 *
 * Informs the application which business requests it can send to the server. The mere presence of this DEG for a
 * particular request means that it can be sent (through PIN/TAN, which is the only mode supported by this library).
 * The $tanErforderlich flag additionally specifies whether a TAN is needed or not.
 */
class GeschaeftsvorfallspezifischePinTanInformationen extends BaseDeg
{
    public string $segmentkennung;

    public bool $tanErforderlich;

    public function getSegmentkennung(): string
    {
        return $this->segmentkennung;
    }

    public function getTanErforderlich(): bool
    {
        return $this->tanErforderlich;
    }
}
