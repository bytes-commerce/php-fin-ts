<?php

namespace Fhp\Segment\AUB;

use Fhp\Segment\BaseDeg;

class ParameterAuslandsueberweisungV2 extends BaseDeg
{
    public int $DTAZVHandbuch;
    public int $maximaleAnzahlTSaetze;
    public float $meldepflichtgrenzbetrag;
    public ?string $unterstuetzteMeldesaetze = null;
    public ?string $zugelasseneWeisungsschluessel = null;
    public ?string $maximaleAnzahlDerZugelassenenWeisungschluessel = null;
    public ?string $erlaubteZahlungsarten = null;

    public function getDTAZVHandbuch(): int
    {
        return $this->DTAZVHandbuch;
    }

    public function getMaximaleAnzahlTSaetze(): int
    {
        return $this->maximaleAnzahlTSaetze;
    }

    public function getMeldepflichtgrenzbetrag(): float
    {
        return $this->meldepflichtgrenzbetrag;
    }

    public function getUnterstuetzteMeldesaetze(): ?string
    {
        return $this->unterstuetzteMeldesaetze;
    }

    public function getZugelasseneWeisungsschluessel(): ?string
    {
        return $this->zugelasseneWeisungsschluessel;
    }

    public function getMaximaleAnzahlDerZugelassenenWeisungschluessel(): ?string
    {
        return $this->maximaleAnzahlDerZugelassenenWeisungschluessel;
    }

    public function getErlaubteZahlungsarten(): ?string
    {
        return $this->erlaubteZahlungsarten;
    }
}
