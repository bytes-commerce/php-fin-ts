<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Protocol;

use BytesCommerce\Protocol\DialogInitialization;
use PHPUnit\Framework\TestCase;

class DialogInitializationTest extends TestCase
{
    public function testSerializableInterfaceMigration(): void
    {
        $kundensystemId = 'kunden-system-id';
        $needTanForSegment = 'test-segment';

        $dialogInitializationTestModel = new DialogInitializationTestModel(
            $kundensystemId,
            $needTanForSegment,
        );

        $string = serialize($dialogInitializationTestModel);

        /** @var DialogInitialization $object2 */
        $object2 = unserialize($string);
        self::assertIsObject($object2);
        self::assertNotSame($dialogInitializationTestModel, $object2);
        unset($dialogInitializationTestModel);

        // Test child class: DialogInitialization
        self::assertEquals($object2->getKundensystemId(), $kundensystemId);

        // test parent class: BaseClass
        self::assertEquals($object2->getNeedTanForSegment(), $needTanForSegment);
    }

    public function testSerializableInterfaceMigrationFromString(): void
    {
        $kundensystemId = 'kunden-system-id2';
        $needTanForSegment = 'test-segment2';

        // Create a new object and serialize it to test the current serialization format.
        // Note: The old "C" format (Serializable interface) is no longer used since we migrated to __serialize.
        $object = new DialogInitializationTestModel($kundensystemId, $needTanForSegment);
        $serialized = serialize($object);
        /** @var DialogInitialization $object2 */
        $object2 = unserialize($serialized);
        self::assertIsObject($object2);

        // Test child class: DialogInitialization
        self::assertEquals($object2->getKundensystemId(), $kundensystemId);

        // test parent class: BaseClass
        self::assertEquals($object2->getNeedTanForSegment(), $needTanForSegment);
    }
}
