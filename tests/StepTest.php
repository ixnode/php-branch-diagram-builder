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

use Exception;
use Throwable;
use Ixnode\PHPBranchDiagramBuilder\Builder;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepEqualSourceAndTargetException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepMissingSourceException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepMissingTargetException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepNotEqualSourceAndTargetException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepUnknownTypeException;
use Ixnode\PHPBranchDiagramBuilder\Exception\StepUnnecessarySourceException;
use Ixnode\PHPBranchDiagramBuilder\Step;
use PHPUnit\Framework\TestCase;

final class StepTest extends TestCase
{
    /**
     * Test the given steps.
     *
     * @phpstan-param ?class-string<Throwable> $exception
     * @param string|null $exception
     * @param string $type
     * @param string|null $source
     * @param string|null $target
     * @param string|null $targetExpected
     * @return void
     * @throws Exception
     * @phpstan-template T of object
     * @dataProvider dataProvider
     */
    public function testStep(?string $exception, string $type, ?string $source, ?string $target, string $targetExpected = null): void
    {
        /* Expected exception */
        if ($exception !== null) {
            $this->expectException($exception);
        }

        /* Arrange */
        $expected = Step::class;

        /* Act */
        $actual = new Step($type, $source, $target);
        $targetExpected = $targetExpected ?? $target;

        /* Assert */
        $this->assertInstanceOf($expected, $actual);
        $this->assertSame($type, $actual->getType());
        $this->assertSame($source, $actual->getSource());
        $this->assertSame($targetExpected, $actual->getTarget());
    }

    /**
     * Define a data provider.
     *
     * @return null[][]|string[][]
     */
    public function dataProvider(): array
    {
        return [
            /* Unknown step type */
            [StepUnknownTypeException::class,             'unknown',                        null,                                Builder::REPOSITORY_BRANCH_MASTER],

            /* Step type init */
            [StepUnnecessarySourceException::class,       Builder::REPOSITORY_NAME_INIT,    Builder::REPOSITORY_BRANCH_MASTER,   Builder::REPOSITORY_BRANCH_MASTER],
            [null,                                        Builder::REPOSITORY_NAME_INIT,    null,                                Builder::REPOSITORY_BRANCH_MASTER],

            /* Step type checkout */
            [StepMissingSourceException::class,           Builder::REPOSITORY_NAME_CHECKOUT, null,                               Builder::REPOSITORY_BRANCH_MASTER],
            [StepMissingTargetException::class,           Builder::REPOSITORY_NAME_CHECKOUT, Builder::REPOSITORY_BRANCH_MASTER,  null],
            [StepEqualSourceAndTargetException::class,    Builder::REPOSITORY_NAME_CHECKOUT, Builder::REPOSITORY_BRANCH_MASTER,  Builder::REPOSITORY_BRANCH_MASTER],
            [null,                                        Builder::REPOSITORY_NAME_CHECKOUT, Builder::REPOSITORY_BRANCH_MASTER,  Builder::REPOSITORY_BRANCH_DEVELOP],

            /* Step type commit */
            [StepMissingSourceException::class,           Builder::REPOSITORY_NAME_COMMIT,   null,                               Builder::REPOSITORY_BRANCH_MASTER],
            [null,                                        Builder::REPOSITORY_NAME_COMMIT,   Builder::REPOSITORY_BRANCH_MASTER,  null,                               Builder::REPOSITORY_BRANCH_MASTER],
            [null,                                        Builder::REPOSITORY_NAME_COMMIT,   Builder::REPOSITORY_BRANCH_MASTER,  Builder::REPOSITORY_BRANCH_MASTER],
            [StepNotEqualSourceAndTargetException::class, Builder::REPOSITORY_NAME_COMMIT,   Builder::REPOSITORY_BRANCH_MASTER,  Builder::REPOSITORY_BRANCH_DEVELOP],

            /* Step type merge */
            [StepMissingSourceException::class,           Builder::REPOSITORY_NAME_MERGE,    null,                               Builder::REPOSITORY_BRANCH_MASTER],
            [StepMissingTargetException::class,           Builder::REPOSITORY_NAME_MERGE,    Builder::REPOSITORY_BRANCH_DEVELOP, null],
            [StepEqualSourceAndTargetException::class,    Builder::REPOSITORY_NAME_MERGE,    Builder::REPOSITORY_BRANCH_DEVELOP, Builder::REPOSITORY_BRANCH_DEVELOP],
            [null,                                        Builder::REPOSITORY_NAME_MERGE,    Builder::REPOSITORY_BRANCH_DEVELOP, Builder::REPOSITORY_BRANCH_MASTER],
        ];
    }
}
