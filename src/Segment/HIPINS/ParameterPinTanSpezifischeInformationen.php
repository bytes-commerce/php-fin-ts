<?php

declare(strict_types=1);

/** @noinspection PhpUnused */
namespace BytesCommerce\Segment\HIPINS;

use BytesCommerce\Segment\BaseDeg;

/**
 * Data Element Group: Parameter PIN/TAN-spezifische Informationen
 *
 * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_PINTAN_2018-02-23_final_version.pdf
 * Section: B.8.1
 */
class ParameterPinTanSpezifischeInformationen extends BaseDeg
{
    public ?int $minimalePinLaenge = null;

    public ?int $maximalePinLaenge = null;

    public ?int $maximaleTanLaenge = null;

    public ?string $textZurBelegungDerBenutzerkennung = null;

    public ?string $textZurBelegungDerKundenId = null;

    /** @var GeschaeftsvorfallspezifischePinTanInformationen[] @Max(999) */
    public array $geschaeftsvorfallspezifischePinTanInformationen;

    public function getMinimalePinLaenge(): ?int
    {
        return $this->minimalePinLaenge;
    }

    public function getMaximalePinLaenge(): ?int
    {
        return $this->maximalePinLaenge;
    }

    public function getMaximaleTanLaenge(): ?int
    {
        return $this->maximaleTanLaenge;
    }

    public function getTextZurBelegungDerBenutzerkennung(): ?string
    {
        return $this->textZurBelegungDerBenutzerkennung;
    }

    public function getTextZurBelegungDerKundenId(): ?string
    {
        return $this->textZurBelegungDerKundenId;
    }

    /**
     * @return GeschaeftsvorfallspezifischePinTanInformationen[]
     */
    public function getGeschaeftsvorfallspezifischePinTanInformationen(): array
    {
        return $this->geschaeftsvorfallspezifischePinTanInformationen;
    }
}
