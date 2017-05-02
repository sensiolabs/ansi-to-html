<?php

/*
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter;

use SensioLabs\AnsiConverter\Theme\Theme;

/**
 * Converts an ANSI text to HTML5.
 */
class AnsiToHtmlConverter
{
    protected $theme;
    protected $charset;
    protected $inlineStyles;
    protected $inlineColors;
    protected $colorNames;
    protected $cssPrefix;

    public function __construct(Theme $theme = null, $inlineStyles = true, $charset = 'UTF-8', $cssPrefix = 'ansi_color')
    {
        if (null === $theme) {
            // If no theme supplied create one and use the default css prefix.
            $this->theme = new Theme($cssPrefix);
            $this->cssPrefix = $cssPrefix;
        } else {
            // Use the supplied theme and the themes prefix if it is defined.
            $this->theme = $theme;
            $this->cssPrefix = $theme->getPrefix();
            if (null === $this->cssPrefix) {
                // Set the prefix on the theme and use the prefix locally.
                $this->theme->setPrefix($cssPrefix);
                $this->cssPrefix = $cssPrefix;
            }
        }
        $this->inlineStyles = $inlineStyles;
        $this->charset = $charset;
        $this->inlineColors = $this->theme->asArray();
        $this->colorNames = array(
            'black', 'red', 'green', 'yellow', 'blue', 'magenta', 'cyan', 'white',
            '', '',
            'brblack', 'brred', 'brgreen', 'bryellow', 'brblue', 'brmagenta', 'brcyan', 'brwhite',
        );
    }

    public function convert($text)
    {
        // remove cursor movement sequences
        $text = preg_replace('#\e\[(K|s|u|2J|2K|\d+(A|B|C|D|E|F|G|J|K|S|T)|\d+;\d+(H|f))#', '', $text);
        // remove character set sequences
        $text = preg_replace('#\e(\(|\))(A|B|[0-2])#', '', $text);

        $text = htmlspecialchars($text, PHP_VERSION_ID >= 50400 ? ENT_QUOTES | ENT_SUBSTITUTE : ENT_QUOTES, $this->charset);

        // carriage return
        $text = preg_replace('#^.*\r(?!\n)#m', '', $text);

        $tokens = $this->tokenize($text);

        // a backspace remove the previous character but only from a text token
        foreach ($tokens as $i => $token) {
            if ('backspace' == $token[0]) {
                $j = $i;
                while (--$j >= 0) {
                    if ('text' == $tokens[$j][0] && strlen($tokens[$j][1]) > 0) {
                        $tokens[$j][1] = substr($tokens[$j][1], 0, -1);

                        break;
                    }
                }
            }
        }

        $html = '';
        foreach ($tokens as $token) {
            if ('text' == $token[0]) {
                $html .= $token[1];
            } elseif ('color' == $token[0]) {
                $html .= $this->convertAnsiToColor($token[1]);
            }
        }

        if ($this->inlineStyles) {
            $html = sprintf('<span style="background-color: %s; color: %s">%s</span>', $this->inlineColors['black'], $this->inlineColors['white'], $html);
        } else {
            $html = sprintf('<span class="%1$s_bg_black %1$s_fg_white">%2$s</span>', $this->cssPrefix, $html);
        }

        // remove empty span
        $html = preg_replace('#<span[^>]*></span>#', '', $html);

        return $html;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    protected function convertAnsiToColor($ansi)
    {
        $bg = 0;
        $fg = 7;
        $as = '';
        if ('0' != $ansi && '' != $ansi) {
            $options = explode(';', $ansi);

            foreach ($options as $option) {
                if ($option >= 30 && $option < 38) {
                    $fg = $option - 30;
                } elseif ($option >= 40 && $option < 48) {
                    $bg = $option - 40;
                } elseif (39 == $option) {
                    $fg = 7;
                } elseif (49 == $option) {
                    $bg = 0;
                }
            }

            // options: bold => 1, underscore => 4, blink => 5, reverse => 7, conceal => 8
            if (in_array(1, $options)) {
                $fg += 10;
                $bg += 10;
            }

            if (in_array(4, $options)) {
                $as = '; text-decoration: underline';
            }

            if (in_array(7, $options)) {
                $tmp = $fg;
                $fg = $bg;
                $bg = $tmp;
            }
        }

        if ($this->inlineStyles) {
            return sprintf(
                '</span><span style="background-color: %s; color: %s%s">',
                $this->inlineColors[$this->colorNames[$bg]],
                $this->inlineColors[$this->colorNames[$fg]],
                $as
            );
        } else {
            return sprintf(
                '</span><span class="%1$s_bg_%2$s %1$s_fg_%3$s%4$s">',
                $this->cssPrefix,
                $this->colorNames[$bg],
                $this->colorNames[$fg],
                ($as ? sprintf(' %1$s_underlined', $this->cssPrefix) : '')
            );
        }
    }

    protected function tokenize($text)
    {
        $tokens = array();
        preg_match_all("/(?:\e\[(.*?)m|(\x08))/", $text, $matches, PREG_OFFSET_CAPTURE);

        $offset = 0;
        foreach ($matches[0] as $i => $match) {
            if ($match[1] - $offset > 0) {
                $tokens[] = array('text', substr($text, $offset, $match[1] - $offset));
            }
            $tokens[] = array("\x08" == $match[0] ? 'backspace' : 'color', $matches[1][$i][0]);
            $offset = $match[1] + strlen($match[0]);
        }
        if ($offset < strlen($text)) {
            $tokens[] = array('text', substr($text, $offset));
        }

        return $tokens;
    }
}
