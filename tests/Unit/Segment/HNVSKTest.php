<?php

namespace BytesCommerce\Tests\Unit\Segment;

use BytesCommerce\Options\Credentials;
use BytesCommerce\Options\FinTsOptions;
use BytesCommerce\Segment\HNVSK\HNVSKv3;
use BytesCommerce\Segment\HNVSK\SchluesselnameV3;
use PHPUnit\Framework\TestCase;

/**
 * Among other things, this test covers the serialization of Bin values.
 */
class HNVSKTest extends TestCase
{
    /**
     * @link https://www.hbci-zka.de/dokumente/spezifikation_deutsch/fintsv3/FinTS_3.0_Security_Sicherheitsverfahren_PINTAN_2018-02-23_final_version.pdf
     * Section: F.2.2 a)
     */
    public const HBCI22_EXAMPLE = "HNVSK:998:3+PIN:1+998+1+1::2+1:20020610:102044+2:2:13:@8@00000000:5:1+280:10020030:12345:V:0:0+0'";

    public function testParse(): void
    {
        $hnvsKv3 = HNVSKv3::parse(static::HBCI22_EXAMPLE);
        $this->assertEquals('00000000', $hnvsKv3->verschluesselungsalgorithmus->wertDesAlgorithmusparametersSchluessel->getData());
        $this->assertEquals('280', $hnvsKv3->schluesselname->kreditinstitutskennung->laenderkennzeichen);
        $this->assertEquals('10020030', $hnvsKv3->schluesselname->kreditinstitutskennung->kreditinstitutscode);
        $this->assertEquals('12345', $hnvsKv3->schluesselname->benutzerkennung);
        $this->assertEquals(SchluesselnameV3::CHIFFRIERSCHLUESSEL, $hnvsKv3->schluesselname->schluesselart);
    }

    public function testSerialize(): void
    {
        $finTsOptions = new FinTsOptions();
        $finTsOptions->bankCode = '10020030';

        $credentials = Credentials::create('12345', 'NOT USED');
        $hnvsKv3 = HNVSKv3::create($finTsOptions, $credentials, '2', null);
        $hnvsKv3->sicherheitsdatumUndUhrzeit->datum = '20020610';
        $hnvsKv3->sicherheitsdatumUndUhrzeit->uhrzeit = '102044';
        $this->assertEquals( // Replace binary zeros to make the diff readable in case the unit test fails.
            str_replace("\0", '0', static::HBCI22_EXAMPLE),
            str_replace("\0", '0', $hnvsKv3->serialize())
        );
    }
}
