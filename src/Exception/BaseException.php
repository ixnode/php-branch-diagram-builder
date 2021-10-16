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

use Exception;

/**
 * Class BaseException
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Exception
 * @package  Ixnode\PHPBranchDiagramBuilder\Exception
 */
abstract class BaseException extends Exception
{
    /* General codes */
    public const RETURN_CODE_NULL = 100;

    /* Step codes */
    public const RETURN_CODE_STEP_UNKNOWN_TYPE = 101;
    public const RETURN_CODE_STEP_MISSING_SOURCE = 102;
    public const RETURN_CODE_STEP_UNNECESSARY_SOURCE = 103;
    public const RETURN_CODE_STEP_MISSING_TARGET = 104;
    public const RETURN_CODE_STEP_EQUAL_SOURCE_AND_TARGET = 105;
    public const RETURN_CODE_STEP_NOT_EQUAL_SOURCE_AND_TARGET = 106;
    public const RETURN_CODE_FUNCTION_DOES_NOT_EXIST = 107;

    /**
     * Returns the return code of current exception.
     *
     * @return int
     */
    abstract public function getReturnCode(): int;
}
