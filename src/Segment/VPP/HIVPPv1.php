<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\VPP;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Segment\Common\Tsp;
use BytesCommerce\Syntax\Bin;

/**
 * Segment: Namensabgleich Prüfergebnis
 *
 * @see FinTS_3.0_Messages_Geschaeftsvorfaelle_VOP_1.01_2025_06_27_FV.pdf
 * Section: C.10.7.1 b)
 */
class HIVPPv1 extends BaseSegment
{
    public ?Bin $vopId = null;

    public ?Tsp $vopIdGueltigBis = null;

    public ?Bin $pollingId = null;

    public ?string $paymentStatusReportDescriptor = null;

    public ?Bin $paymentStatusReport = null;

    public ?ErgebnisVopPruefungEinzeltransaktionV1 $ergebnisVopPruefungEinzeltransaktion = null;

    public ?string $aufklaerungstextAutorisierungTrotzAbweichung = null;

    // This value is in seconds
    public ?int $wartezeitVorNaechsterAbfrage = null;
}
