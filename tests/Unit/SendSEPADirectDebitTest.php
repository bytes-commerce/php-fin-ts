<?php

namespace BytesCommerce\Tests\Unit;

use BytesCommerce\Action\SendSEPADirectDebit;
use BytesCommerce\Model\SEPAAccount;

class SendSEPADirectDebitTest extends FinTsTestCase
{
    public function testCanSendLargeFiles(): void
    {
        $sepaAccount = new SEPAAccount();

        $painString = file_get_contents(__DIR__ . '/../resources/pain.008.002.02.xml');

        $sendSEPADirectDebit = SendSEPADirectDebit::create($sepaAccount, $painString);

        $this->assertInstanceOf(SendSEPADirectDebit::class, $sendSEPADirectDebit);
    }
}
