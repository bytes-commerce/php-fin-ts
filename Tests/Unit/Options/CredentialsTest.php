<?php

namespace Fhp\Tests\Unit\Options;

use Fhp\Options\Credentials;
use PHPUnit\Framework\TestCase;

class CredentialsTest extends TestCase
{
    public function testCreateWithValidCredentials(): void
    {
        $credentials = Credentials::create('user123', 'secretpin');

        $this->assertSame('user123', $credentials->getBenutzerkennung());
        $this->assertSame('secretpin', $credentials->getPin());
    }

    public function testCreateWithEmptyBenutzerkennungThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('benutzerkennung cannot be empty');

        Credentials::create('', 'secretpin');
    }

    public function testCreateWithEmptyPinThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('pin cannot be empty');

        Credentials::create('user123', '');
    }

    public function testCreateWithNumericBenutzerkennung(): void
    {
        $credentials = Credentials::create('12345678', 'mypin');

        $this->assertSame('12345678', $credentials->getBenutzerkennung());
    }

    public function testCreateWithSpecialCharactersInPin(): void
    {
        $credentials = Credentials::create('user123', 'p@ssw0rd!');

        $this->assertSame('p@ssw0rd!', $credentials->getPin());
    }

    public function testDebugInfoReturnsNull(): void
    {
        $credentials = Credentials::create('user123', 'secretpin');

        $this->assertNull($credentials->__debugInfo());
    }
}
