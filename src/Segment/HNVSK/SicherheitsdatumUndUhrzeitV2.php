<?php

declare(strict_types=1);


/** @noinspection PhpUnused */

// NOTE: In FinTsTestCase, this namespace name is hard-coded in order to be able to mock the time() function below.

namespace BytesCommerce\Segment\HNVSK;

use BytesCommerce\Segment\BaseDeg;

/**
 * Data Element Group: Sicherheitsdatum und -uhrzeit (Version 2)
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_HBCI_Rel_20181129_final_version.pdf
 * Section: D
 */
class SicherheitsdatumUndUhrzeitV2 extends BaseDeg
{
    /**
     * 1: Sicherheitszeitstempel (STS)
     * 6: Certificate Revocation Time (CRT)
     */
    public int $datumUndZeitbezeichner = 1; // This library does not support recovation, so STS is all we need.
    /** JJJJMMTT gemäß ISO 8601 */
    public ?string $datum = null;

    /** hhmmss gemäß ISO 8601, local time (no time zone support). */
    public ?string $uhrzeit = null;

    /**
     * @return SicherheitsdatumUndUhrzeitV2 For the current time.
     */
    public static function now(): SicherheitsdatumUndUhrzeitV2
    {
        $sicherheitsdatumUndUhrzeitV2 = new SicherheitsdatumUndUhrzeitV2();
        try {
            $now = new \DateTime('@' . time()); // Call unqualified time() for unit test mocking to work.
            $sicherheitsdatumUndUhrzeitV2->datum = $now->format('Ymd');
            $sicherheitsdatumUndUhrzeitV2->uhrzeit = $now->format('His');
        } catch (\Exception $exception) {
            throw new \RuntimeException('Failed to get current date', 0, $exception);
        }

        return $sicherheitsdatumUndUhrzeitV2;
    }
}
