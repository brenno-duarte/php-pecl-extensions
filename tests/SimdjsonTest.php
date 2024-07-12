<?php

declare(strict_types=1);

namespace PeclPolyfillTest;

use PeclPolyfill\Functions\Simdjson\Simdjson;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SimdjsonTest extends TestCase
{
    public function test_it_decodes_json(): void
    {
        $json = '{"id": 1}';

        $object = Simdjson::simdjsonDecode($json);
        $this->assertIsObject($object);
        $this->assertSame(1, $object->id);

        $array = Simdjson::simdjsonDecode($json, true);
        $this->assertIsArray($array);
        $this->assertSame(1, $array['id']);
    }

    #[DataProvider("providerJsonIsValid")]
    public function test_it_checks_if_json_is_valid(string $json, $expected): void
    {
        $this->assertSame($expected, Simdjson::simdjsonIsValid($json));
    }

    public static function providerJsonIsValid(): array
    {
        return [
            [
                'json' => '{}',
                'expected' => true,
            ],
            [
                'json' => '[]',
                'expected' => true,
            ],
            [
                'json' => '[{"id": 1}]',
                'expected' => true,
            ],
            [
                'json' => '{"id": 1}',
                'expected' => true,
            ],
            [
                'json' => '[["id": 1]',
                'expected' => false,
            ],
            [
                'json' => '{"id": 1',
                'expected' => false,
            ],
            [
                'json' => "Invalid String",
                'expected' => false,
            ],
        ];
    }

    #[DataProvider("providerKeyValueArray")]
    public function test_it_returns_key_value_array(string $json, string $key, $expected): void
    {
        $this->assertSame($expected, Simdjson::simdjsonKeyValue($json, $key, true));
    }

    public static function providerKeyValueArray(): array
    {
        $data = [
            'result' => [
                [
                    'id' => 1,
                    'value' => 'test',
                ],
                [
                    'id' => 2,
                    'value' => 'other',
                ],
            ],
            'count' => 2,
        ];

        $json = json_encode($data);

        return [
            [
                'json' => $json,
                'key' => 'result',
                'expected' => $data['result'],
            ],
            [
                'json' => $json,
                'key' => 'result/1',
                'expected' => $data['result'][1],
            ],
            [
                'json' => $json,
                'key' => 'result/0/id',
                'expected' => $data['result'][0]['id'],
            ],
            [
                'json' => $json,
                'key' => 'count',
                'expected' => $data['count'],
            ],
        ];
    }

    public function providerKeyValueObject(): array
    {
        $data = [
            'result' => [
                [
                    'id' => 1,
                    'value' => 'test',
                ],
                [
                    'id' => 2,
                    'value' => 'other',
                ],
            ],
            'count' => 2,
        ];

        $json = json_encode($data);

        return [
            [
                'json' => $json,
                'key' => 'result',
                'expected' => json_decode(json_encode($data['result'])),
            ],
            [
                'json' => $json,
                'key' => 'result/1',
                'expected' => json_decode(json_encode($data['result'][1])),
            ],
            [
                'json' => $json,
                'key' => 'result/0/id',
                'expected' => $data['result'][0]['id'],
            ],
        ];
    }

    public function test_it_returns_key_value_object(): void
    {
        $data = [
            'result' => [
                [
                    'id' => 1,
                    'value' => 'test',
                ],
                [
                    'id' => 2,
                    'value' => 'other',
                ],
            ],
            'count' => 2,
        ];

        $json = json_encode($data);

        $data = Simdjson::simdjsonKeyValue($json, 'result', false);
        $this->assertCount(2, $data);
        $this->assertSame(1, $data[0]->id);
        $this->assertSame(2, $data[1]->id);

        $data = Simdjson::simdjsonKeyValue($json, 'result/1', false);
        $this->assertIsObject($data);
        $this->assertSame('other', $data->value);

        $data = Simdjson::simdjsonKeyValue($json, 'result/0/id', false);
        $this->assertSame(1, $data);

        $data = Simdjson::simdjsonKeyValue($json, 'count', false);
        $this->assertSame(2, $data);
    }

    public function test_it_counts_keys(): void
    {
        $data = [
            'result' => [
                [
                    'id' => 1,
                    'value' => 'test',
                ],
                [
                    'id' => 2,
                    'value' => 'other',
                ],
            ],
            'count' => 2,
        ];

        $json = json_encode($data);

        $count = Simdjson::simdjsonKeyCount($json, 'result');
        $this->assertSame(2, $count);

        $count = Simdjson::simdjsonKeyCount($json, 'result/0');
        $this->assertSame(2, $count);
    }

    #[DataProvider("providerKeyExists")]
    public function test_it_checks_if_key_exists(string $json, string $key, $expected): void
    {
        $this->assertSame($expected, Simdjson::simdjsonKeyExists($json, $key));
    }

    public static function providerKeyExists(): array
    {
        $data = [
            'result' => [
                [
                    'id' => 1,
                    'value' => 'test',
                ],
                [
                    'id' => 2,
                    'value' => 'other',
                ],
            ],
            'count' => 2,
        ];

        $json = json_encode($data);

        return [
            [
                'json' => $json,
                'key' => 'result',
                'expected' => true,
            ],
            [
                'json' => $json,
                'key' => 'result/1',
                'expected' => true,
            ],
            [
                'json' => $json,
                'key' => 'result/0/id',
                'expected' => true,
            ],
            [
                'json' => $json,
                'key' => 'count',
                'expected' => true,
            ],
            [
                'json' => $json,
                'key' => 'result/3',
                'expected' => false,
            ],
            [
                'json' => $json,
                'key' => 'unknown',
                'expected' => false,
            ],
            [
                'json' => $json,
                'key' => 'result/0/unknown',
                'expected' => false,
            ],
        ];
    }
}
