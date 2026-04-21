<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\CSE;

use BytesCommerce\Segment\BaseSegment;
use BytesCommerce\Syntax\Bin;

/**
 * Segment: SEPA Einzelüberweisung (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
 * Section: C.10.2.1 a)
 */
class HKCSEv1 extends BaseSegment
{
    /** IBAN/BIC must match <DbtrAcct> and <DbtrAgt> in the XML Below. */
    public \BytesCommerce\Segment\Common\Kti $kontoverbindungInternational;

    /** Max length: 256 */
    public string $sepaDescriptor;

    /**
     * The PAIN message in XML format.
     * HISPAS informs which XML schemas are allowed.
     * The <ReqdExctnDt> field must be 1999-01-01.
     */
    public Bin $sepaPainMessage;
}
