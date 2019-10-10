<?php

/**
 * This file is part of ansi-to-html.
 *
 * (c) 2013 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SensioLabs\AnsiConverter\Bridge\Twig;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AnsiExtension extends AbstractExtension
{
    private $converter;

    public function __construct(AnsiToHtmlConverter $converter = null)
    {
        $this->converter = $converter ?: new AnsiToHtmlConverter();
    }

    public function getFilters()
    {
        return [
            new TwigFilter('ansi_to_html', [$this, 'ansiToHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('ansi_css', [$this, 'css'], ['is_safe' => ['css']]),
        ];
    }

    public function ansiToHtml($string)
    {
        return $this->converter->convert($string);
    }

    public function css()
    {
        return $this->converter->getTheme()->asCss();
    }

    public function getName()
    {
        return 'sensiolabs_ansi';
    }
}
