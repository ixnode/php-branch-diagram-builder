<?php declare(strict_types=1);

/*
 * MIT License
 *
 * Copyright (c) 2021 Björn Hempel <bjoern@hempel.li>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Test\Ixnode\PhpBranchDiagramBuilder;

use Ixnode\PHPBranchDiagramBuilder\Branch;
use Ixnode\PHPBranchDiagramBuilder\Builder;
use PHPUnit\Framework\TestCase;

final class BranchTest extends TestCase
{
    /**
     * Test branch class.
     */
    public function testBranch(): void
    {
        /* Arrange */
        $fillColor = Builder::CONNECTION_FILL_COLOR;
        $strokeColor = Builder::CONNECTION_STROKE_COLOR;
        $expected = new Branch($fillColor, $strokeColor);

        /* Act */
        $actual = new Branch($fillColor, $strokeColor);

        /* Assert */
        $this->assertEquals($expected, $actual);
        $this->assertEquals($fillColor, $actual->getFillColor());
        $this->assertEquals($strokeColor, $actual->getStrokeColor());
    }

    /**
     * Test branch class with title.
     */
    public function testBranchName(): void
    {
        /* Arrange */
        $fillColor = Builder::CONNECTION_FILL_COLOR;
        $strokeColor = Builder::CONNECTION_STROKE_COLOR;
        $name = 'Name';

        /* Act */
        $actual = new Branch($fillColor, $strokeColor);
        $actual->setName($name);

        /* Assert */
        $this->assertEquals($name, $actual->getName());
    }

    /**
     * Test branch class with title.
     */
    public function testBranchTitle(): void
    {
        /* Arrange */
        $fillColor = Builder::CONNECTION_FILL_COLOR;
        $strokeColor = Builder::CONNECTION_STROKE_COLOR;
        $title = 'Title';

        /* Act */
        $actual = new Branch($fillColor, $strokeColor);
        $actual->setTitle($title);

        /* Assert */
        $this->assertEquals($title, $actual->getTitle());
    }
}
