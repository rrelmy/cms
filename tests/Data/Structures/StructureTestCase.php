<?php

namespace Tests\Data\Structures;

use Tests\TestCase;

abstract class StructureTestCase extends TestCase
{
    abstract public function structure($handle = null);

    /** @test */
    public function the_tree_root_cannot_have_children_when_expecting_root()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Root page cannot have children');

        $this->structure()->expectsRoot(true)->validateTree([
            [
                'entry' => '123',
                'children' => [
                    [
                        'entry' => '456',
                    ],
                ],
            ],
        ], 'en');
    }

    /** @test */
    public function the_tree_root_can_have_children_when_not_expecting_root()
    {
        $tree = [
            [
                'entry' => '123',
                'children' => [
                    [
                        'entry' => '456',
                    ],
                ],
            ],
        ];

        $this->assertEquals($tree, $this->structure('test')->expectsRoot(false)->validateTree($tree, 'en'));
    }

    /** @test */
    public function the_root_must_be_an_entry_when_expecting_root()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Root page must be an entry');

        $this->structure('test')->expectsRoot(true)->validateTree([
            [
                'title' => 'Not an entry',
                'url' => '/test',
            ],
        ], 'en');
    }

    /** @test */
    public function the_root_doesnt_need_to_be_an_entry_if_the_tree_is_empty()
    {
        $this->assertEquals([], $this->structure('test')->expectsRoot(true)->validateTree([], 'en'));
    }

    /** @test **/
    public function the_root_doesnt_need_to_be_an_entry_when_not_expecting_root()
    {
        $tree = [
            [
                'title' => 'Not an entry',
                'url' => '/test',
            ],
        ];

        $this->assertEquals($tree, $this->structure('test')->expectsRoot(false)->validateTree($tree, 'en'));
    }
}
