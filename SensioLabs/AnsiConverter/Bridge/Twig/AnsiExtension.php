<?php

namespace SensioLabs\AnsiConverter\Bridge\Twig;

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;

class AnsiExtension extends \Twig_Extension
{
    private $converter;

    public function __construct(AnsiToHtmlConverter $converter = null)
    {
        $this->converter = $converter ?: new AnsiToHtmlConverter();
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ansi_to_html', array($this, 'ansiToHtml'), array('is_safe' => array('html'))),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ansi_css', array($this, 'css'), array('is_safe' => array('css'))),
        );
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
