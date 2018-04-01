<?php
/**
 * User: aierui
 * Email: aieruishi@gmail.com
 * Date: 2018/3/24
 * Time: 下午7:55
 */

namespace Tests;

use PHPUtils\Arr;
use PHPUnit\Framework\TestCase;


class ArrayTest extends TestCase
{
    public function testUnique()
    {
        $this->assertSame([1, 2, 3, 4], Arr::unique([1, 2, 3, 4, 1, 2]));
        $this->assertSame([1, 2, 3, 4], Arr::unique([1, 2, 3, 4, 1, 2], true));
    }

    public function testSort()
    {
        $array = [1, 2, 56, 222, 546];
        $this->assertSame([1, 2, 56, 222, 546], Arr::sort($array));

        $lists = [
            1 => [
                'create_time' => '2018-03-01 12:21:00',
            ],
            2 => [
                'create_time' => '2018-03-02 12:21:00',
            ],
            3 => [
                'create_time' => '2018-03-03 12:21:00',
            ],
            4 => [
                'create_time' => '2018-03-04 12:21:00',
            ]
        ];

        $this->assertSame(
            [
                4 => [
                    'create_time' => '2018-03-04 12:21:00',
                ],
                3 => [
                    'create_time' => '2018-03-03 12:21:00',
                ],
                2 => [
                    'create_time' => '2018-03-02 12:21:00',
                ],
                1 => [
                    'create_time' => '2018-03-01 12:21:00',
                ]
            ],
            Arr::sort($lists, function ($a, $b) {
                return $a['create_time'] < $b['create_time'] ? 1 : -1;
            })
        );
    }


    public function testAccessible()
    {
        $this->assertTrue(Arr::accessible([1, 2, 3, 4, 5]));
        $this->assertNotTrue(Arr::accessible('12345'));
    }


    public function testExists()
    {
        $this->assertTrue(Arr::exists([1, 2, 3, 4, 5], 1));
        $this->assertNotTrue(Arr::exists([1, 2, 3, 4, 5], 6));

    }

    public function testIsAssoc()
    {
        $this->assertTrue(Arr::isAssoc(
            [
                1 => 1,
                2 => 2,
            ]
        ));
        $this->assertNotTrue(Arr::isAssoc([1, 2, 3, 4, 5]));
    }

    public function testMerge()
    {
        $arr1 = ['id' => 1, 'data' => 'value1', 'keys' => ['A1', 'B1', 'C1']];
        $arr2 = ['id' => 2, 'data' => 'value2', 'keys' => ['A2', 'B2', 'C2']];
        $arr3 = ['id' => 3, 'data' => 'value3', 'keys' => ['A3', 'B3', 'C3']];

        $this->assertSame(
            [
                'id' => 3,
                'data' => 'value3',
                'keys' => ['A1', 'B1', 'C1', 'A2', 'B2', 'C2', 'A3', 'B3', 'C3']
            ],
            Arr::merge($arr1, $arr2, $arr3)
        );

        $arr4 = [
            4 => [
                'id' => 4,
                'data' => 'data4',
            ],
            5 => [
                'id' => 5,
                'data' => 'data5',
            ],
        ];


        $arr5 = [
            5 => [
                'id' => 6,
                'data' => 'data6',
            ],
            7 => [
                'id' => 7,
                'data' => 'data7',
            ],
        ];

        $this->assertSame(
            [
                4 => [
                    'id' => 4,
                    'data' => 'data4',
                ],
                5 => [
                    'id' => 6,
                    'data' => 'data6',
                ],
                7 => [
                    'id' => 7,
                    'data' => 'data7',
                ],

            ],
            Arr::merge($arr4, $arr5)
        );
    }


    public function testRemoveEmpty()
    {
        $array = [1, 2, 3, 4, ''];
        Arr::removeEmpty($array);
        $this->assertSame([1, 2, 3, 4], $array);
    }


    public function testFilterEmpty()
    {
        $array = [1, 2, 3, 4, ''];
        $this->assertSame([1, 2, 3, 4], Arr::filterEmpty($array, false));
        $this->assertSame(['A', 'B', 'C', 'D'], Arr::filterEmpty(['A', 'B', 'C', '', 'D', '']));
    }


}