<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class requirementsTest extends TestCase
{

    /**
     * Checks MbString installed as a extension on php.ini
     */
    public function testMbString(): void
    {
        $this->assertTrue(
            extension_loaded("mbstring"),
            "mb_string is not installed"
        );
    }
}
