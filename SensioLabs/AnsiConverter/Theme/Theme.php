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
    /**
     * @var string
     */
    protected $prefix;

    public function __construct($prefix = null)
    {
        $this->prefix = $prefix;
    }

    public function asCss()
    {
        $css = array();
        foreach ($this->asArray() as $name => $color) {
            $css[] = sprintf('.%s_fg_%s { color: %s }', $this->prefix, $name, $color);
            $css[] = sprintf('.%s_bg_%s { background-color: %s }', $this->prefix, $name, $color);
        }
        $css[] = sprintf('.%s_underlined { text-decoration: underlined }', $this->prefix);

        return implode("\n", $css);
    }

    public function asArray()
    {
        return array(
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
        );
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}
