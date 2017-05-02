<?php

/*
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter\Tests\DefaultTheme;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

class AnsiToHtmlConverterWithClassesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConvertData
     */
    public function testConvert($expectedOutput, $expectedCss, $input)
    {
        $converter = new AnsiToHtmlConverter(null, false);
        $this->assertEquals($expectedOutput, $converter->convert($input));
        $this->assertEquals($expectedCss, $converter->getTheme()->asCss());
    }

    public function getConvertData()
    {
        $css = <<< 'END_CSS'
.ansi_color_fg_black { color: black }
.ansi_color_bg_black { background-color: black }
.ansi_color_fg_red { color: darkred }
.ansi_color_bg_red { background-color: darkred }
.ansi_color_fg_green { color: green }
.ansi_color_bg_green { background-color: green }
.ansi_color_fg_yellow { color: yellow }
.ansi_color_bg_yellow { background-color: yellow }
.ansi_color_fg_blue { color: blue }
.ansi_color_bg_blue { background-color: blue }
.ansi_color_fg_magenta { color: darkmagenta }
.ansi_color_bg_magenta { background-color: darkmagenta }
.ansi_color_fg_cyan { color: cyan }
.ansi_color_bg_cyan { background-color: cyan }
.ansi_color_fg_white { color: white }
.ansi_color_bg_white { background-color: white }
.ansi_color_fg_brblack { color: black }
.ansi_color_bg_brblack { background-color: black }
.ansi_color_fg_brred { color: red }
.ansi_color_bg_brred { background-color: red }
.ansi_color_fg_brgreen { color: lightgreen }
.ansi_color_bg_brgreen { background-color: lightgreen }
.ansi_color_fg_bryellow { color: lightyellow }
.ansi_color_bg_bryellow { background-color: lightyellow }
.ansi_color_fg_brblue { color: lightblue }
.ansi_color_bg_brblue { background-color: lightblue }
.ansi_color_fg_brmagenta { color: magenta }
.ansi_color_bg_brmagenta { background-color: magenta }
.ansi_color_fg_brcyan { color: lightcyan }
.ansi_color_bg_brcyan { background-color: lightcyan }
.ansi_color_fg_brwhite { color: white }
.ansi_color_bg_brwhite { background-color: white }
.ansi_color_underlined { text-decoration: underlined }
END_CSS;

        return array(
            // text is escaped
            array('<span class="ansi_color_bg_black ansi_color_fg_white">foo &lt;br /&gt;</span>', $css, 'foo <br />'),

            // newlines are preserved
            array("<span class=\"ansi_color_bg_black ansi_color_fg_white\">foo\nbar</span>", $css, "foo\nbar"),

            // backspaces
            array('<span class="ansi_color_bg_black ansi_color_fg_white">foo   </span>', $css, "foobar\x08\x08\x08   "),
            array('<span class="ansi_color_bg_black ansi_color_fg_white">foo</span><span class="ansi_color_bg_black ansi_color_fg_white">   </span>', $css, "foob\e[31;41ma\e[0mr\x08\x08\x08   "),

            // color
            array('<span class="ansi_color_bg_red ansi_color_fg_red">foo</span>', $css, "\e[31;41mfoo\e[0m"),

            // color with [m as a termination (equivalent to [0m])
            array('<span class="ansi_color_bg_red ansi_color_fg_red">foo</span>', $css, "\e[31;41mfoo\e[m"),

            // bright color
            array('<span class="ansi_color_bg_brred ansi_color_fg_brred">foo</span>', $css, "\e[31;41;1mfoo\e[0m"),

            // carriage returns
            array('<span class="ansi_color_bg_black ansi_color_fg_white">foobar</span>', $css, "foo\rbar\rfoobar"),

            // underline
            array('<span class="ansi_color_bg_black ansi_color_fg_white ansi_color_underlined">foo</span>', $css, "\e[4mfoo\e[0m"),

            // non valid unicode codepoints substitution (only available with PHP >= 5.4)
            PHP_VERSION_ID < 50400 ?: array('<span class="ansi_color_bg_black ansi_color_fg_white">foo '."\xEF\xBF\xBD".'</span>', $css, "foo \xF4\xFF\xFF\xFF"),
        );
    }
}
