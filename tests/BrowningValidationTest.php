<?php

declare(strict_types=1);

namespace Eustasy\Browning\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Covers Browning()'s required-field validation, which returns before any
 * network call — so these need no mock server.
 */
final class BrowningValidationTest extends TestCase
{
    public function testReturnsErrorWhenRecipientMissing(): void
    {
        $result = \Browning('', 'Subject', 'Body');

        $this->assertFalse($result['Success']);
        $this->assertSame(
            'Please provide the following required field: recipient email address.',
            $result['Error']
        );
    }

    public function testUsesPluralWhenTwoFieldsMissing(): void
    {
        $result = \Browning('', '', 'Body');

        $this->assertFalse($result['Success']);
        $this->assertSame(
            'Please provide the following required fields: recipient email address, subject.',
            $result['Error']
        );
    }

    public function testListsAllThreeMissingFieldsInOrder(): void
    {
        $result = \Browning('', '', '');

        $this->assertFalse($result['Success']);
        $this->assertSame(
            'Please provide the following required fields: recipient email address, subject, message body.',
            $result['Error']
        );
    }

    public function testNullArgumentsAreHandledGracefully(): void
    {
        // The ?string parameters tolerate null without raising a TypeError.
        $result = \Browning(null, null, null);

        $this->assertFalse($result['Success']);
        $this->assertIsString($result['Error']);
    }

    public function testZeroStringSubjectIsTreatedAsMissing(): void
    {
        // Documents the PHP quirk that empty('0') === true.
        $result = \Browning('to@example.com', '0', 'Body');

        $this->assertFalse($result['Success']);
        $this->assertStringContainsString('subject', (string) $result['Error']);
    }
}
