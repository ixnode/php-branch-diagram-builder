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

namespace Ixnode\PHPBranchDiagramBuilder\Tools;

use Ixnode\PHPBranchDiagramBuilder\Exception\NullException;

/**
 * Class Converter
 *
 * @author   Björn Hempel <bjoern@hempel.li>
 * @version  1.0 <2021-10-16>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://github.com/ixnode/php-branch-diagram-builder
 * @category Tools
 * @package  Ixnode\PHPBranchDiagramBuilder\Tools
 */
class Converter
{
    /**
     * Replacement for the internal php preg_replace function.
     *
     * @param string $pattern     The pattern.
     * @param string $replacement The replacement.
     * @param string $subject     The subject.
     * @param int    $limit       [optional]
     * @param int    $count       [optional]
     *
     * @return string
     * @throws NullException
     */
    public static function pregReplaceString(
        string $pattern,
        string $replacement,
        string $subject,
        int $limit = -1,
        int &$count = 0
    ): string {
        $value = preg_replace($pattern, $replacement, $subject, $limit, $count);

        if ($value === null) {
            throw new NullException(
                sprintf(
                    NullException::TEXT_NULL_EXCEPTION,
                    NullException::FUNCTION_PREG_REPLACE,
                    __FILE__,
                    __LINE__
                )
            );
        }

        return $value;
    }

    /**
     * Replacement for the internal php preg_replace function.
     *
     * @param string[] $pattern     The pattern.
     * @param string[] $replacement The replacement.
     * @param string[] $subject     the subject.
     * @param int      $limit       [optional]
     * @param int      $count       [optional]
     *
     * @return string[]
     * @throws NullException
     */
    public static function pregReplaceArray(
        array $pattern,
        array $replacement,
        array $subject,
        int $limit = -1,
        int &$count = 0
    ): array {
        $array = preg_replace($pattern, $replacement, $subject, $limit, $count);

        if ($array === null) {
            throw new NullException(
                sprintf(
                    NullException::TEXT_NULL_EXCEPTION,
                    NullException::FUNCTION_PREG_REPLACE,
                    __FILE__,
                    __LINE__
                )
            );
        }

        return $array;
    }

    /**
     * Transform a given key to an UNDER_SCORE key.
     *
     * @param string $keyName The key name.
     *
     * @return string
     * @throws NullException
     */
    public static function getUnderscoredKey(string $keyName): string
    {
        /* Replace capital letters with -capital  */
        $keyName = self::pregReplaceString('~([A-Z][a-z])~', '_$1', $keyName);

        /* Replace number letters with -number  */
        $keyName = self::pregReplaceString('~([A-Za-z])([0-9])~', '$1_$2', $keyName);
        $keyName = self::pregReplaceString('~([0-9])([A-Za-z])~', '$1_$2', $keyName);

        /* Replace all - to _ */
        $keyName = str_replace('-', '_', $keyName);

        /* Replace doubled __ */
        $keyName = self::pregReplaceString('~_+~', '_', $keyName);

        /* Remove _ at the beginning */
        $keyName = self::pregReplaceString('~^_~', '', $keyName);

        /* Transform all letters to upper */
        return strtoupper($keyName);
    }

    /**
     * Removes quotes from string.
     *
     * @param string $string The string.
     *
     * @return string
     * @throws NullException
     */
    public static function removeQuotes(string $string): string
    {
        return self::pregReplaceString('~^[\'"]?(.*?)[\'"]?$~', '$1', $string);
    }

    /**
     * Removes the file extension from given file.
     *
     * @param string $file The file.
     *
     * @return string
     */
    public static function removeFileExtension(string $file): string
    {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        return sprintf(
            "%s/%s",
            dirname($file),
            basename($file, sprintf('.%s', $ext))
        );
    }

    /**
     * Replaces the file extension from given file with the given extension.
     *
     * @param string $file      The file.
     * @param string $extension The extension.
     *
     * @return string
     */
    public static function replaceFileExtension(
        string $file,
        string $extension
    ): string {
        return sprintf('%s.%s', self::removeFileExtension($file), $extension);
    }
}
