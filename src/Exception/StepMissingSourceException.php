<?php

declare(strict_types=1);

/**
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

namespace Ixnode\PHPBranchDiagramBuilder\Exception;

use Throwable;

/**
 * Class StepMissingSourceException
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Exception
 * @package  Ixnode\PHPBranchDiagramBuilder\Exception
 */
class StepMissingSourceException extends BaseException
{
    public const TEXT_STEP_MISSING_SOURCE = <<<TEXT
No source branch was given to current step type.
TEXT;

    /**
     * StepMissingSourceException constructor.
     *
     * @param int            $code     The code of this Exception.
     * @param Throwable|null $previous The Throwable for the previous exception.
     */
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct(self::TEXT_STEP_MISSING_SOURCE, $code, $previous);
    }

    /**
     * Returns the return code of this exception class.
     *
     * @return int
     */
    public function getReturnCode(): int
    {
        return self::RETURN_CODE_STEP_MISSING_SOURCE;
    }
}
