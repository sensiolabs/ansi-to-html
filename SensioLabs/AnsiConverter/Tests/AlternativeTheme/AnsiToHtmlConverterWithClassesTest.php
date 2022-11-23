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

class AnsiToHtmlConverterWithClassesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConvertData
     */
    public function testConvert($expectedOutput, $expectedCss, $input)
    {
        $converter = new AnsiToHtmlConverter(new SolarizedTheme(), false);
        $this->assertEquals($expectedOutput, $converter->convert($input));
        $this->assertEquals($expectedCss, $converter->getTheme()->asCss());
    }

    public function getConvertData()
    {
        $css = <<< 'END_CSS'
.ansi_color_fg_black { color: #073642 }
.ansi_color_bg_black { background-color: #073642 }
.ansi_color_fg_red { color: #dc322f }
.ansi_color_bg_red { background-color: #dc322f }
.ansi_color_fg_green { color: #859900 }
.ansi_color_bg_green { background-color: #859900 }
.ansi_color_fg_yellow { color: #b58900 }
.ansi_color_bg_yellow { background-color: #b58900 }
.ansi_color_fg_blue { color: #268bd2 }
.ansi_color_bg_blue { background-color: #268bd2 }
.ansi_color_fg_magenta { color: #d33682 }
.ansi_color_bg_magenta { background-color: #d33682 }
.ansi_color_fg_cyan { color: #2aa198 }
.ansi_color_bg_cyan { background-color: #2aa198 }
.ansi_color_fg_white { color: #eee8d5 }
.ansi_color_bg_white { background-color: #eee8d5 }
.ansi_color_fg_brblack { color: #002b36 }
.ansi_color_bg_brblack { background-color: #002b36 }
.ansi_color_fg_brred { color: #cb4b16 }
.ansi_color_bg_brred { background-color: #cb4b16 }
.ansi_color_fg_brgreen { color: #586e75 }
.ansi_color_bg_brgreen { background-color: #586e75 }
.ansi_color_fg_bryellow { color: #657b83 }
.ansi_color_bg_bryellow { background-color: #657b83 }
.ansi_color_fg_brblue { color: #839496 }
.ansi_color_bg_brblue { background-color: #839496 }
.ansi_color_fg_brmagenta { color: #6c71c4 }
.ansi_color_bg_brmagenta { background-color: #6c71c4 }
.ansi_color_fg_brcyan { color: #93a1a1 }
.ansi_color_bg_brcyan { background-color: #93a1a1 }
.ansi_color_fg_brwhite { color: #fdf6e3 }
.ansi_color_bg_brwhite { background-color: #fdf6e3 }
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

            // Yellow on green.
            array('<span class="ansi_color_bg_green ansi_color_fg_yellow">foo</span>', $css, "\e[33;42mfoo\e[0m"),

            // Yellow on green - reversed.
            array('<span class="ansi_color_bg_yellow ansi_color_fg_green">foo</span>', $css, "\e[33;42;7mfoo\e[0m"),
        );
    }
}
