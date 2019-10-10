<?php

/**
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter\Tests;

use PHPUnit\Framework\TestCase;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedTheme;
use SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme;

class AnsiToHtmlConverterTest extends TestCase
{
    /**
     * @dataProvider getConvertDataStandardTheme
     */
    public function testConvert($expected, $input)
    {
        $converter = new AnsiToHtmlConverter();
        $this->assertEquals($expected, $converter->convert($input));
    }

    /**
     * @dataProvider getConvertDataWithSolarizedTheme
     */
    public function testConvertWithSetTheme($expected, $input)
    {
        $converter = new AnsiToHtmlConverter();
        $converter->setTheme(new SolarizedTheme());
        $this->assertEquals($expected, $converter->convert($input));
    }

    /**
     * @dataProvider getConvertDataWithSolarizedXTermTheme
     */
    public function testConvertWithInjectedTheme($expected, $input)
    {
        $converter = new AnsiToHtmlConverter(new SolarizedXTermTheme());
        $this->assertEquals($expected, $converter->convert($input));
    }

    public function getConvertDataStandardTheme()
    {
        return [
            // text is escaped
            ['<span style="background-color: black; color: white">foo &lt;br /&gt;</span>', 'foo <br />'],

            // newlines are preserved
            ["<span style=\"background-color: black; color: white\">foo\nbar</span>", "foo\nbar"],

            // backspaces
            ['<span style="background-color: black; color: white">foo   </span>', "foobar\x08\x08\x08   "],
            ['<span style="background-color: black; color: white">foo</span><span style="background-color: black; color: white">   </span>', "foob\e[31;41ma\e[0mr\x08\x08\x08   "],

            // color
            ['<span style="background-color: darkred; color: darkred">foo</span>', "\e[31;41mfoo\e[0m"],

            // color with [m as a termination (equivalent to [0m])
            ['<span style="background-color: darkred; color: darkred">foo</span>', "\e[31;41mfoo\e[m"],

            // bright color
            ['<span style="background-color: red; color: red">foo</span>', "\e[31;41;1mfoo\e[0m"],

            // carriage returns
            ['<span style="background-color: black; color: white">foobar</span>', "foo\rbar\rfoobar"],

            // underline
            ['<span style="background-color: black; color: white; text-decoration: underline">foo</span>', "\e[4mfoo\e[0m"],

            // non valid unicode codepoints substitution (only available with PHP >= 5.4)
            \PHP_VERSION_ID < 50400 ? ['', ''] : ['<span style="background-color: black; color: white">foo '."\xEF\xBF\xBD".'</span>', "foo \xF4\xFF\xFF\xFF"],
        ];
    }

    public function getConvertDataWithSolarizedTheme()
    {
        return [
            // text is escaped
            ['<span style="background-color: #073642; color: #eee8d5">foo &lt;br /&gt;</span>', 'foo <br />'],

            // newlines are preserved
            ["<span style=\"background-color: #073642; color: #eee8d5\">foo\nbar</span>", "foo\nbar"],

            // backspaces
            ['<span style="background-color: #073642; color: #eee8d5">foo   </span>', "foobar\x08\x08\x08   "],
            ['<span style="background-color: #073642; color: #eee8d5">foo</span><span style="background-color: #073642; color: #eee8d5">   </span>', "foob\e[31;41ma\e[0mr\x08\x08\x08   "],

            // color
            ['<span style="background-color: #dc322f; color: #dc322f">foo</span>', "\e[31;41mfoo\e[0m"],

            // color with [m as a termination (equivalent to [0m])
            ['<span style="background-color: #dc322f; color: #dc322f">foo</span>', "\e[31;41mfoo\e[m"],

            // bright color
            ['<span style="background-color: #cb4b16; color: #cb4b16">foo</span>', "\e[31;41;1mfoo\e[0m"],

            // carriage returns
            ['<span style="background-color: #073642; color: #eee8d5">foobar</span>', "foo\rbar\rfoobar"],

            // underline
            ['<span style="background-color: #073642; color: #eee8d5; text-decoration: underline">foo</span>', "\e[4mfoo\e[0m"],

            // non valid unicode codepoints substitution (only available with PHP >= 5.4)
            \PHP_VERSION_ID < 50400 ? ['', ''] : ['<span style="background-color: #073642; color: #eee8d5">foo '."\xEF\xBF\xBD".'</span>', "foo \xF4\xFF\xFF\xFF"],
        ];
    }

    public function getConvertDataWithSolarizedXTermTheme()
    {
        return [
            // text is escaped
            ['<span style="background-color: #262626; color: #e4e4e4">foo &lt;br /&gt;</span>', 'foo <br />'],

            // newlines are preserved
            ["<span style=\"background-color: #262626; color: #e4e4e4\">foo\nbar</span>", "foo\nbar"],

            // backspaces
            ['<span style="background-color: #262626; color: #e4e4e4">foo   </span>', "foobar\x08\x08\x08   "],
            ['<span style="background-color: #262626; color: #e4e4e4">foo</span><span style="background-color: #262626; color: #e4e4e4">   </span>', "foob\e[31;41ma\e[0mr\x08\x08\x08   "],

            // color
            ['<span style="background-color: #d70000; color: #d70000">foo</span>', "\e[31;41mfoo\e[0m"],

            // color with [m as a termination (equivalent to [0m])
            ['<span style="background-color: #d70000; color: #d70000">foo</span>', "\e[31;41mfoo\e[m"],

            // bright color
            ['<span style="background-color: #d75f00; color: #d75f00">foo</span>', "\e[31;41;1mfoo\e[0m"],

            // carriage returns
            ['<span style="background-color: #262626; color: #e4e4e4">foobar</span>', "foo\rbar\rfoobar"],

            // underline
            ['<span style="background-color: #262626; color: #e4e4e4; text-decoration: underline">foo</span>', "\e[4mfoo\e[0m"],

            // non valid unicode codepoints substitution (only available with PHP >= 5.4)
            \PHP_VERSION_ID < 50400 ? ['', ''] : ['<span style="background-color: #262626; color: #e4e4e4">foo '."\xEF\xBF\xBD".'</span>', "foo \xF4\xFF\xFF\xFF"],
        ];
    }
}
