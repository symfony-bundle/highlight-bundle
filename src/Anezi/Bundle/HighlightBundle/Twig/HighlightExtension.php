<?php
/**
 * This file is part of the Highlight Bundle for Symfony.
 *
 * @copyright   Copyright (C) 2014 Hassan Amouhzi. All rights reserved.
 * @license     The MIT License (MIT), see LICENSE.md
 */
 
namespace Anezi\Bundle\HighlightBundle\Twig;

use Anezi\Bundle\HighlightBundle\Twig\TokenParser\HighlightTokenParser;
use Pygments\Pygmentize;
use Symfony\Component\HttpKernel\Kernel;

class HighlightExtension extends \Twig_Extension
{

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    function getTokenParsers() {
        return array(
            new HighlightTokenParser(),
        );
    }

    public function getFilters()
    {
        return array(
            'highlight'  => new \Twig_Filter_Method($this, 'highlight', array('is_safe' => array('html')))
        );
    }

    public function getFunctions()
    {
        return array(
            'highlight'  => new \Twig_Function_Method($this, 'highlight', array('is_safe' => array('html')))
        );
    }

    public function getName()
    {
        return 'highlight_extension';
    }

    public function highlight($source, $lexer)
    {
        $hash = sha1($source);
        if(!file_exists($this->kernel->getCacheDir() . '/highlight')) {
            mkdir($this->kernel->getCacheDir() . '/highlight');
        }
        $cache = $this->kernel->getCacheDir() . '/highlight/' . $hash;
        if(file_exists($cache)) {
            $highlight = file_get_contents($cache);
        } else {
            $highlight = Pygmentize::format($source, $lexer);
            file_put_contents($cache, $highlight);
        }
        return
            '<div class="highlight"><pre><code class="' . $lexer . '">' .
            $highlight .
            '</code></pre></div>';
    }
}
