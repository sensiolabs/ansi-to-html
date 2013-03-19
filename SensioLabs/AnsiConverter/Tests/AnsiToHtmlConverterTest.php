<?php

/*
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter\Tests;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

class AnsiToHtmlConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConvertData
     */
    public function testConvert($expected, $input)
    {
        $converter = new AnsiToHtmlConverter();
        $this->assertEquals($expected, $converter->convert($input));
    }

    public function getConvertData()
    {
        return array(
            // text is escaped
            array('<span style="background-color: black; color: white">foo &lt;br /&gt;</span>', 'foo <br />'),

            // newlines are preserved
            array("<span style=\"background-color: black; color: white\">foo\nbar</span>", "foo\nbar"),

            // backspaces
            array("<span style=\"background-color: black; color: white\">foo   </span>", "foobar\x08\x08\x08   "),
            array("<span style=\"background-color: black; color: white\">foo</span><span style=\"background-color: black; color: white\">   </span>", "foob\e[31;41ma\e[0mr\x08\x08\x08   "),

            // color
            array("<span style=\"background-color: red; color: red\">foo</span>", "\e[31;41mfoo\e[0m"),

            // bright color
            array("<span style=\"background-color: red; color: red\">foo</span>", "\e[31;41;1mfoo\e[0m"),
        );
    }
}
