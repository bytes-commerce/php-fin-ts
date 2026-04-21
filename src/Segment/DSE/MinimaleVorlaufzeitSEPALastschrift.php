<?php

declare(strict_types=1);



namespace BytesCommerce\Segment\DSE;

class MinimaleVorlaufzeitSEPALastschrift
{
    /**
     * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
     * Section: D ("Unterstützte SEPA-Lastschriftarten, codiert")
     */
    public const UNTERSTUETZTE_SEPA_LASTSCHRIFTARTEN_CODIERT = [
        ['CORE'],
        ['COR1'],
        ['CORE', 'COR1'],
    ];

    /**
     * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Messages_Geschaeftsvorfaelle_2015-08-07_final_version.pdf
     * Section: D ("SequenceType, codiert")
     */
    public const SEQUENCE_TYPE_CODIERT = [
        ['FNAL', 'RCUR', 'FRST', 'OOFF'],
        ['FNAL', 'RCUR'],
        ['FRST', 'OOFF'],
    ];

    /** Must be 0,1,2 */
    public int $unterstuetzteSEPALastschriftartenCodiert;

    /** Must be 0,1,2 */
    public int $sequenceTypeCodiert;

    /** In Days */
    public int $minimaleSEPAVorlaufzeit;

    /** After this time the request will fail when the value of is used, for example 130000 meaning 1pm */
    public string $cutOffZeit;

    public function getUnterstuetzteSEPALastschriftartenCodiert(): int
    {
        return $this->unterstuetzteSEPALastschriftartenCodiert;
    }

    public function getSequenceTypeCodiert(): int
    {
        return $this->sequenceTypeCodiert;
    }

    public function getMinimaleSEPAVorlaufzeit(): int
    {
        return $this->minimaleSEPAVorlaufzeit;
    }

    public function getCutOffZeit(): string
    {
        return $this->cutOffZeit;
    }

    public static function create(int $minimaleSEPAVorlaufzeit, string $cutOffZeit, ?int $unterstuetzteSEPALastschriftartenCodiert = null,
        ?int $sequenceTypeCodiert = null): MinimaleVorlaufzeitSEPALastschrift
    {
        $minimaleVorlaufzeitSEPALastschrift = new MinimaleVorlaufzeitSEPALastschrift();
        $minimaleVorlaufzeitSEPALastschrift->unterstuetzteSEPALastschriftartenCodiert = $unterstuetzteSEPALastschriftartenCodiert;
        $minimaleVorlaufzeitSEPALastschrift->sequenceTypeCodiert = $sequenceTypeCodiert;
        $minimaleVorlaufzeitSEPALastschrift->minimaleSEPAVorlaufzeit = $minimaleSEPAVorlaufzeit;
        $minimaleVorlaufzeitSEPALastschrift->cutOffZeit = $cutOffZeit;

        return $minimaleVorlaufzeitSEPALastschrift;
    }

    /** @return MinimaleVorlaufzeitSEPALastschrift[][]|array */
    public static function parseCoded(string $coded): array
    {
        $result = [];
        foreach (array_chunk(explode(';', $coded), 4) as [$unterstuetzteSEPALastschriftartenCodiert, $sequenceTypeCodiert, $minimaleSEPAVorlaufzeit, $cutOffZeit]) {
            $coreTypes = self::UNTERSTUETZTE_SEPA_LASTSCHRIFTARTEN_CODIERT[$unterstuetzteSEPALastschriftartenCodiert] ?? [];
            $seqTypes = self::SEQUENCE_TYPE_CODIERT[$sequenceTypeCodiert] ?? [];
            foreach ($coreTypes as $coreType) {
                foreach ($seqTypes as $seqType) {
                    $result[$coreType][$seqType] = MinimaleVorlaufzeitSEPALastschrift::create((int) $minimaleSEPAVorlaufzeit, $cutOffZeit, (int) $unterstuetzteSEPALastschriftartenCodiert, (int) $sequenceTypeCodiert);
                }
            }
        }

        return $result;
    }

    /** @return MinimaleVorlaufzeitSEPALastschrift[][]|array */
    public static function parseCodedB2B(string $coded): array
    {
        $result = [];
        foreach (array_chunk(explode(';', $coded), 3) as [$sequenceTypeCodiert, $minimaleSEPAVorlaufzeit, $cutOffZeit]) {
            $seqTypes = self::SEQUENCE_TYPE_CODIERT[$sequenceTypeCodiert] ?? [];
            foreach ($seqTypes as $seqType) {
                $result['B2B'][$seqType] = MinimaleVorlaufzeitSEPALastschrift::create((int) $minimaleSEPAVorlaufzeit, $cutOffZeit, null, (int) $sequenceTypeCodiert);
            }
        }

        return $result;
    }
}
