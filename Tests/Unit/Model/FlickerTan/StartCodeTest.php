<?php

namespace Fhp\Tests\Unit\Model\FlickerTan;

use Fhp\Model\FlickerTan\StartCode;
use PHPUnit\Framework\TestCase;

class StartCodeTest extends TestCase
{
    public function testParseNextBlockWithUnsupportedVersion(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Other versions then 1.4 are not supported');

        $challenge = '9F02123456789012345';
        StartCode::parseNextBlock($challenge);
    }
}
