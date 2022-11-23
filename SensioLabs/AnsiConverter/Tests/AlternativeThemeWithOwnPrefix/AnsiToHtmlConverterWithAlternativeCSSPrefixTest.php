<?php

/*
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter\Tests\AlternativeThemeWithOwnPrefix;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedXTermTheme;

class AnsiToHtmlConverterWithAlternativeCSSPrefixTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getConvertData
     */
    public function testConvert($expectedOutput, $expectedCss, $input)
    {
        $converter = new AnsiToHtmlConverter(new SolarizedXTermTheme('dalek'), false, 'UTF-8', 'alternative_prefix');
        $this->assertEquals($expectedOutput, $converter->convert($input));
        $this->assertEquals($expectedCss, $converter->getTheme()->asCss());
    }

    public function getConvertData()
    {
        $css = <<< 'END_CSS'
.dalek_fg_black { color: #262626 }
.dalek_bg_black { background-color: #262626 }
.dalek_fg_red { color: #d70000 }
.dalek_bg_red { background-color: #d70000 }
.dalek_fg_green { color: #5f8700 }
.dalek_bg_green { background-color: #5f8700 }
.dalek_fg_yellow { color: #af8700 }
.dalek_bg_yellow { background-color: #af8700 }
.dalek_fg_blue { color: #0087ff }
.dalek_bg_blue { background-color: #0087ff }
.dalek_fg_magenta { color: #af005f }
.dalek_bg_magenta { background-color: #af005f }
.dalek_fg_cyan { color: #00afaf }
.dalek_bg_cyan { background-color: #00afaf }
.dalek_fg_white { color: #e4e4e4 }
.dalek_bg_white { background-color: #e4e4e4 }
.dalek_fg_brblack { color: #1c1c1c }
.dalek_bg_brblack { background-color: #1c1c1c }
.dalek_fg_brred { color: #d75f00 }
.dalek_bg_brred { background-color: #d75f00 }
.dalek_fg_brgreen { color: #585858 }
.dalek_bg_brgreen { background-color: #585858 }
.dalek_fg_bryellow { color: #626262 }
.dalek_bg_bryellow { background-color: #626262 }
.dalek_fg_brblue { color: #808080 }
.dalek_bg_brblue { background-color: #808080 }
.dalek_fg_brmagenta { color: #5f5faf }
.dalek_bg_brmagenta { background-color: #5f5faf }
.dalek_fg_brcyan { color: #8a8a8a }
.dalek_bg_brcyan { background-color: #8a8a8a }
.dalek_fg_brwhite { color: #ffffd7 }
.dalek_bg_brwhite { background-color: #ffffd7 }
.dalek_underlined { text-decoration: underlined }
END_CSS;

        return array(
            // text is escaped
            array('<span class="dalek_bg_black dalek_fg_white">foo &lt;br /&gt;</span>', $css, 'foo <br />'),

            // newlines are preserved
            array("<span class=\"dalek_bg_black dalek_fg_white\">foo\nbar</span>", $css, "foo\nbar"),

            // backspaces
            array('<span class="dalek_bg_black dalek_fg_white">foo   </span>', $css, "foobar\x08\x08\x08   "),
            array('<span class="dalek_bg_black dalek_fg_white">foo</span><span class="dalek_bg_black dalek_fg_white">   </span>', $css, "foob\e[31;41ma\e[0mr\x08\x08\x08   "),

            // color
            array('<span class="dalek_bg_red dalek_fg_red">foo</span>', $css, "\e[31;41mfoo\e[0m"),

            // color with [m as a termination (equivalent to [0m])
            array('<span class="dalek_bg_red dalek_fg_red">foo</span>', $css, "\e[31;41mfoo\e[m"),

            // bright color
            array('<span class="dalek_bg_brred dalek_fg_brred">foo</span>', $css, "\e[31;41;1mfoo\e[0m"),

            // carriage returns
            array('<span class="dalek_bg_black dalek_fg_white">foobar</span>', $css, "foo\rbar\rfoobar"),

            // underline
            array('<span class="dalek_bg_black dalek_fg_white dalek_underlined">foo</span>', $css, "\e[4mfoo\e[0m"),

            // non valid unicode codepoints substitution (only available with PHP >= 5.4)
            PHP_VERSION_ID < 50400 ?: array('<span class="dalek_bg_black dalek_fg_white">foo '."\xEF\xBF\xBD".'</span>', $css, "foo \xF4\xFF\xFF\xFF"),

            // Yellow on green.
            array('<span class="dalek_bg_green dalek_fg_yellow">foo</span>', $css, "\e[33;42mfoo\e[0m"),

            // Yellow on green - reversed.
            array('<span class="dalek_bg_yellow dalek_fg_green">foo</span>', $css, "\e[33;42;7mfoo\e[0m"),
        );
    }
}
