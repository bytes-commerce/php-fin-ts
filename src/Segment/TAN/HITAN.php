<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\TAN;

use BytesCommerce\Model\TanRequest;

interface HITAN extends TanRequest
{
    public const DUMMY_REFERENCE = 'noref';

    public const DUMMY_CHALLENGE = 'nochallenge';

    public function getTanProzess(): string;

    public function getAuftragsreferenz(): ?string;
}
