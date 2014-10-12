<?php

namespace Anezi\Bundle\HighlightBundle\Twig\Node;

class HighlightNode extends \Twig_Node
{
    public function __construct(\Twig_Node $source, \Twig_Node $lexer, $lineNo = 0, $tag)
    {
        parent::__construct(
            array(
                'source' => $source,
                'lexer' => $lexer
            ),
            array(),
            $lineNo,
            $tag
        );
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler $compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler
            ->raw('ob_start();' . PHP_EOL)
            ->subcompile($this->getNode('source'))
            ->raw('$source = ob_get_contents();' . PHP_EOL)
            ->raw('ob_clean();' . PHP_EOL)
            ->raw('echo $this->env->getExtension(\'highlight_extension\')->highlight(' . PHP_EOL)
            ->raw('   $source,' . PHP_EOL)
            ->raw('   \'' . $this->getNode('lexer')->getAttribute('name') . '\' ' . PHP_EOL)
            ->raw(');' . PHP_EOL);
    }
}
