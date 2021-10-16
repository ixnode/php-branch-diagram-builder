<?php declare(strict_types=1);

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
 *
 * PHP version 8
 *
 * @category TypeCheck
 * @package  Ixnode\PHPBranchDiagramBuilder\TypeCheck
 * @author   Björn Hempel <bjoern@hempel.li>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  GIT: 1.0.0
 * @link     https://www.hempel.li
 */

namespace Ixnode\PHPBranchDiagramBuilder\TypeCheck;

/**
 * Class TypeCheck
 *
 * @category TypeCheck
 * @package  Ixnode\PHPBranchDiagramBuilder\TypeCheck
 * @author   Björn Hempel <bjoern@hempel.li>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @version  Release: @package_version@
 * @link     https://www.hempel.li
 */
class TypeCheck
{
    /**
     * Returns whether the given $value is boolean type.
     *
     * @param bool $value The value.
     *
     * @return bool
     */
    public static function isBoolean(bool $value): bool
    {
        return is_bool($value);
    }
}
