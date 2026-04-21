<?php

declare(strict_types=1);

namespace BytesCommerce\Segment\CAZ;

use BytesCommerce\Segment\BaseDeg;
use BytesCommerce\Syntax\Bin;

/**
 * Needed for
 * Issue: GetStatementOfAccountXML receiving multiple CAMT XML files
 * @link: https://github.com/nemiah/phpFinTS/issues/370
 *
 * @link: https://github.com/nemiah/phpFinTS/pull/371
 */
class GebuchteCamtUmsaetze extends BaseDeg
{
    /** Allow for up to 999 Binary fields (XML files) */
    /** @var Bin[] @Max(999) */
    public array $gebuchteCamtUmsaetze;

    /**
     * @return string[]
     */
    public function getData(): array
    {
        $xml = [];
        foreach ($this->gebuchteCamtUmsaetze as $bin) {
            $xml[] = $bin->getData();
        }

        return $xml;
    }
}
