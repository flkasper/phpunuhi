<?php

declare(strict_types=1);

namespace PHPUnuhi\Tests\Traits;

use PHPUnit\Framework\TestCase;
use PHPUnuhi\Traits\ArrayTrait;

class ArrayTraitTest extends TestCase
{
    use ArrayTrait;


    public function testGetFlatArray(): void
    {
        $array = [
            'title' => 'Title',
            'content' => [
                'headline' => 'A content',
                'description' => 'this is my description'
            ]
        ];

        $expected = [
            'title' => 'Title',
            'content.headline' => 'A content',
            'content.description' => 'this is my description',
        ];

        $flat = $this->getFlatArray($array, '.');

        $this->assertEquals($expected, $flat);
    }


    public function testGetMultiDimensionalArray(): void
    {
        $array = [
            'title' => 'Title',
            'content.headline' => 'A content',
            'content.description' => 'this is my description',
        ];

        $expected = [
            'title' => 'Title',
            'content' => [
                'headline' => 'A content',
                'description' => 'this is my description'
            ]
        ];

        $dimensional = $this->getMultiDimensionalArray($array, '.');

        $this->assertEquals($expected, $dimensional);
    }


    public function testGetMultiDimensionalWithDifferentDelimiter(): void
    {
        $array = [
            'card.title' => 'Title',
            'card2,title' => 'Title 2',
        ];

        $expected = [
            'card.title' => 'Title',
            'card2' => [
                'title' => 'Title 2',
            ]
        ];

        $dimensional = $this->getMultiDimensionalArray($array, ',');

        $this->assertEquals($expected, $dimensional);
    }


    public function testGetMultiDimensionalWithEmptyDelimiter(): void
    {
        $array = [
            'card.title' => 'Title',
        ];

        $expected = [
            'card.title' => 'Title',
        ];

        $dimensional = $this->getMultiDimensionalArray($array, '');

        $this->assertEquals($expected, $dimensional);
    }


    public function testGetMultiDimensionalWith2NestedLevels(): void
    {
        $array = [
            'sub.sub2' => 'Title',
            'sub.subsub.test' => 'Title',
            'title' => 'Title',
        ];

        $expected = [
            'sub' => [
                'sub2' => 'Title',
                'subsub' => [
                    'test' => 'Title'
                ]
            ],
            'title' => 'Title',
        ];

        $dimensional = $this->getMultiDimensionalArray($array, '.');

        $this->assertEquals($expected, $dimensional);
    }

    public function testGetLineNumbers(): void
    {
        $array = [
            'title' => 'Title',
            'content' => [
                'headline' => 'A content',
                'description' => 'this is my description'
            ]
        ];

        $expected = [
            'title' => 1,
            'content.headline' => 3,
            'content.description' => 4,
            '__LINE_NUMBER__' => 4,
        ];

        $flat = $this->getLineNumbers($array, '.');

        $this->assertEquals($expected, $flat);
    }

    public function testGetLineNumbersWithOffset(): void
    {
        $array = [
            'title' => 'Title',
            'content' => [
                'headline' => 'A content',
                'description' => 'this is my description'
            ]
        ];

        $expected = [
            'title' => 2,
            'content.headline' => 4,
            'content.description' => 5,
            '__LINE_NUMBER__' => 5,
        ];

        $flat = $this->getLineNumbers($array, '.', '', 1);

        $this->assertEquals($expected, $flat);
    }

    public function testGetLineNumbersWithClosingBrackets(): void
    {
        $array = [
            'title' => 'Title',
            'content' => [
                'headline' => 'A content',
                'description' => 'this is my description'
            ],
            'content2' => [
                'headline' => 'A content',
                'description' => 'this is my description'
            ],
        ];

        $expected = [
            'title' => 1,
            'content.headline' => 3,
            'content.description' => 4,
            'content2.headline' => 7,
            'content2.description' => 8,
            '__LINE_NUMBER__' => 9,
        ];

        $flat = $this->getLineNumbers($array, '.', '', 0, true);

        $this->assertEquals($expected, $flat);
    }

    public function testNestedArrayWithListOfArray(): void
    {
        $array = [
            'blocks.0.title' => 'I am a title',
            'blocks.0.content' => 'I am some content',
        ];

        $expected = [
            'blocks' => [
                [
                    'title' => 'I am a title',
                    'content' => 'I am some content',
                ]
            ],
        ];

        $actual = $this->getMultiDimensionalArray($array, '.');

        $this->assertEquals($expected, $actual);
        ;
    }
}
