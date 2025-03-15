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
 * This theme uses ANSI-like colors.
 */
class AnsiTheme extends Theme
{
    public function asArray()
    {
        return array(
            // normal
            'black' => '#000000',
            'red' => '#AA0000',
            'green' => '#00AA00',
            'yellow' => '#AAAA00',
            'blue' => '#0000AA',
            'magenta' => '#AA00AA',
            'cyan' => '#00AAAA',
            'white' => '#AAAAAA',

            // bright
            'brblack' => '#555555',
            'brred' => '#FF5555',
            'brgreen' => '#55FF55',
            'bryellow' => '#FFFF55',
            'brblue' => '#5555FF',
            'brmagenta' => '#FF55FF',
            'brcyan' => '#55FFFF',
            'brwhite' => '#FFFFFF',
        );
    }
}
