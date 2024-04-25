<?php

namespace Flow\JSONPath\Test;

require_once dirname(__DIR__, 2) . "/vendor/autoload.php";

use PeclPolyfill\JSONPath\JSONPath;
use PHPUnit\Framework\TestCase;

class JSONPathDashedIndexTest extends TestCase
{
    public static function indexDataProvider()
    {
        return [
            // path, data, expected
            [
                '$.data[test-test-test]',
                [
                    'data' => [
                        'test-test-test' => 'foo'
                    ]
                ],
                [
                    'foo'
                ]
            ],
            [
                '$.data[40f35757-2563-4790-b0b1-caa904be455f]',
                [
                    'data' => [
                        '40f35757-2563-4790-b0b1-caa904be455f' => 'bar'
                    ]
                ],
                [
                    'bar'
                ]
            ]
        ];
    }

    /** @dataProvider indexDataProvider */
    public function testSlice($path, $data, $expected)
    {
        $jsonPath = new JSONPath($data);
        $result = $jsonPath->find($path)->data();
        $this->assertEquals($expected, $result);
    }
}
