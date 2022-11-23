<?php

/*
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter\Tests\AlternativeTheme;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedTheme;

class AnsiToHtmlConverterWithInlineStylesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConvertData
     */
    public function testConvertWith($expected, $input)
    {
        $converter = new AnsiToHtmlConverter(new SolarizedTheme());
        $this->assertEquals($expected, $converter->convert($input));
    }

    public function getConvertData()
    {
        return array(
            // text is escaped
            array('<span style="background-color: #073642; color: #eee8d5">foo &lt;br /&gt;</span>', 'foo <br />'),

            // newlines are preserved
            array("<span style=\"background-color: #073642; color: #eee8d5\">foo\nbar</span>", "foo\nbar"),

            // backspaces
            array('<span style="background-color: #073642; color: #eee8d5">foo   </span>', "foobar\x08\x08\x08   "),
            array('<span style="background-color: #073642; color: #eee8d5">foo</span><span style="background-color: #073642; color: #eee8d5">   </span>', "foob\e[31;41ma\e[0mr\x08\x08\x08   "),

            // color
            array('<span style="background-color: #dc322f; color: #dc322f">foo</span>', "\e[31;41mfoo\e[0m"),

            // color with [m as a termination (equivalent to [0m])
            array('<span style="background-color: #dc322f; color: #dc322f">foo</span>', "\e[31;41mfoo\e[m"),

            // bright color
            array('<span style="background-color: #cb4b16; color: #cb4b16">foo</span>', "\e[31;41;1mfoo\e[0m"),

            // carriage returns
            array('<span style="background-color: #073642; color: #eee8d5">foobar</span>', "foo\rbar\rfoobar"),

            // underline
            array('<span style="background-color: #073642; color: #eee8d5; text-decoration: underline">foo</span>', "\e[4mfoo\e[0m"),

            // non valid unicode codepoints substitution (only available with PHP >= 5.4)
            PHP_VERSION_ID < 50400 ?: array('<span style="background-color: #073642; color: #eee8d5">foo '."\xEF\xBF\xBD".'</span>', "foo \xF4\xFF\xFF\xFF"),

            // Yellow on green.
            array('<span style="background-color: #859900; color: #b58900">foo</span>', "\e[33;42mfoo\e[0m"),

            // Yellow on green - reversed.
            array('<span style="background-color: #b58900; color: #859900">foo</span>', "\e[33;42;7mfoo\e[0m"),
        );
    }
}
