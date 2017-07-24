<?php
/**
 * Created by PhpStorm.
 * User: name
 * Date: 24/07/17
 * Time: 12.41
 */

class UploadSimulatorTest extends PHPUnit\Framework\TestCase
{
    static $TEST_RESOURCE_ID = '1234567890';
    static $TEST_RESOURCE_ID_PATH = __DIR__ . '/../../../resources/1234567890.txt';

    protected function setUp()
    {
        $test_resource = self::$TEST_RESOURCE_ID_PATH;
        `touch ${test_resource}`;
        $this->assertFileExists($test_resource);
    }

    protected function tearDown()
    {
        $test_resource = self::$TEST_RESOURCE_ID_PATH;
        `rm -f ${test_resource}`;
        $this->assertFileNotExists($test_resource);
    }

    public function test_upload_simplified()
    {
        $this->assertEquals(42, 42);
        $test_resource = self::$TEST_RESOURCE_ID_PATH;
        $test_resource_id = self::$TEST_RESOURCE_ID;

        /**
         * @var $uploader_mock \aqquadro\UploadSimulator
         */
        $uploader_mock = $this->getMockBuilder(\aqquadro\UploadSimulator::class)
            ->disableOriginalConstructor()
            ->setMethods(['upload'])
            ->getMock();

        $calls_count = 0;

        $uploader_mock->expects($this->exactly(2))
            ->method('upload')
            ->withConsecutive(
                [
                    'gold_bucket',
                    "cdn/${test_resource_id}",
                    $this->callback(function ($fp) use ($test_resource, &$calls_count) {
                        $calls_count++;
                        $this->assertEquals('resource', gettype($fp), "FIRST on callcount n.${calls_count}");
                        $this->assertNotEquals('Unknown', get_resource_type($fp), "FIRST on callcount n.${calls_count}");
                        $this->assertFileEquals($test_resource, stream_get_meta_data($fp)['uri']);
                        return true;
                    })
                ],
                [
                    'gold_bucket',
                    "cdn/${test_resource_id}",
                    $this->callback(function ($fp) use ($test_resource, &$calls_count) {
                        $calls_count++;
                        $this->assertEquals('resource', gettype($fp), "SECOND on callcount n.${calls_count}");
                        $this->assertNotEquals('Unknown', get_resource_type($fp), "SECOND on callcount n.${calls_count}");
                        $this->assertFileEquals($test_resource, stream_get_meta_data($fp)['uri']);
                        return true;
                    })
                ]
            )
            ->willReturn(true);

        $uploader_result = $uploader_mock->upload_simplified($test_resource_id);
        $this->assertTrue($uploader_result);

        $uploader_result = $uploader_mock->upload_simplified($test_resource_id);
        $this->assertTrue($uploader_result);

        $this->assertEquals(2, $calls_count);
    }
}