<?php

/*
 * This file is part of ansi-to-html.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter\Theme;

/**
 * Base theme.
 */
class Theme
{
    public function asCss($prefix = 'ansi_color')
    {
        $css = [];
        foreach ($this->asArray() as $name => $color) {
            $css[] = \sprintf('.%s_fg_%s { color: %s }', $prefix, $name, $color);
            $css[] = \sprintf('.%s_bg_%s { background-color: %s }', $prefix, $name, $color);
        }

        $css[] = \sprintf('.%s_italic { font-style: italic }', $prefix);
        $css[] = \sprintf('.%s_underline { text-decoration: underline }', $prefix);
        $css[] = \sprintf('.%s_strikethrough { text-decoration: line-through }', $prefix);

        return implode("\n", $css);
    }

    public function asArray()
    {
        return [
            'black' => 'black',
            'red' => 'darkred',
            'green' => 'green',
            'yellow' => 'yellow',
            'blue' => 'blue',
            'magenta' => 'darkmagenta',
            'cyan' => 'cyan',
            'white' => 'white',

            'brblack' => 'black',
            'brred' => 'red',
            'brgreen' => 'lightgreen',
            'bryellow' => 'lightyellow',
            'brblue' => 'lightblue',
            'brmagenta' => 'magenta',
            'brcyan' => 'lightcyan',
            'brwhite' => 'white',
        ];
    }
}
