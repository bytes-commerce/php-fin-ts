<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\HIRMS;

use BytesCommerce\Segment\BaseDeg;

/**
 * Data Element Group: Rückmeldung (Version 1)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Formals_2017-10-06_final_version.pdf
 * Section: F (under letter R)
 */
class Rueckmeldung extends BaseDeg implements \Stringable
{
    /** See also the Rueckmeldungscode class/enum. */
    public int $rueckmeldungscode;

    /**
     * O: bei Verwendung im Segment HIRMS
     * N: bei Verwendung im Segment HIRMG
     * Max length: 7
     */
    public ?string $bezugsdatenelement = null;

    /** Max length: 80 */
    public string $rueckmeldungstext;

    /** @var string[]|null @Max(10), max length each: 35 */
    public ?array $rueckmeldungsparameter = null;

    /**
     * This is not part of the FinTS wire format, but for convenience we store it here. If this Rueckmeldung pertains to
     * a particular segment of the request, then this will be its segment number.
     * @Ignore
     */
    public ?int $referenceSegment = null;

    public function __toString(): string
    {
        $referenceSegment = $this->referenceSegment === null ? 'global' : 'wrt seg ' . $this->referenceSegment;
        $result = sprintf('%d (%s): %s', $this->rueckmeldungscode, $referenceSegment, $this->rueckmeldungstext);
        if ($this->bezugsdatenelement !== null) {
            $result .= sprintf(' (wrt DE %s)', $this->bezugsdatenelement);
        }

        if ($this->rueckmeldungsparameter !== null && $this->rueckmeldungsparameter !== []) {
            $result .= ' [' . implode(', ', $this->rueckmeldungsparameter) . ']';
        }

        return $result;
    }
}
