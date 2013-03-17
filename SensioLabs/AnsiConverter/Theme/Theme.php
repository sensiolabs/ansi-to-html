<?php

/*
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
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
        $css = array();
        foreach ($this->asArray() as $name => $color) {
            $css[] = sprintf('.%s_fg_%s { color: %s }', $prefix, $name, $color);
            $css[] = sprintf('.%s_bg_%s { background-color: %s }', $prefix, $name, $color);
        }

        return implode("\n", $css);
    }

    public function asArray()
    {
        return array(
            'black'   => 'black',
            'red'     => 'red',
            'green'   => 'green',
            'yellow'  => 'yellow',
            'blue'    => 'blue',
            'magenta' => 'magenta',
            'cyan'    => 'cyan',
            'white'   => 'white',

            'brblack'   => 'black',
            'brred'     => 'red',
            'brgreen'   => 'green',
            'bryellow'  => 'yellow',
            'brblue'    => 'blue',
            'brmagenta' => 'magenta',
            'brcyan'    => 'cyan',
            'brwhite'   => 'white',
        );
    }
}
