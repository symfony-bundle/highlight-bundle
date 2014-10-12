<?php

namespace Anezi\Bundle\HighlightBundle\Twig\TokenParser;

use Anezi\Bundle\HighlightBundle\Twig\Node\HighlightNode;
use Pygments\Pygmentize;

class HighlightTokenParser extends \Twig_TokenParser
{
    /**
     * Parses a token and returns a node.
     *
     * @param \Twig_Token $token A Twig_Token instance
     * @throws \Exception
     * @throws \Twig_Error_Syntax
     * @return \Twig_NodeInterface A Twig_NodeInterface instance
     */
    public function parse(\Twig_Token $token)
    {
        $lineNo = $token->getLine();
        $stream = $this->parser->getStream();

        $pygmentizeLexers = array_keys(Pygmentize::lexers());

        $acceptedLexers = array();
        foreach($pygmentizeLexers as $lexer) {
            $k = str_replace('+', '', $lexer);
            $k = str_replace('-', '', $k);
            $k = str_replace(' ', '', $k);
            $acceptedLexers[$k] = $lexer;
        }

        $additionalLexers = array(
            'twig' => 'jinja',
            'twightml' => 'html+jinja',
            'htmltwig' => 'html+jinja',
            'composer' => 'json',
            'objcpp' => 'obj-c++',
            'objectivecpp' => 'objective-c++'
        );

        $lexer = null;

        if (!$stream->test(\Twig_Token::BLOCK_END_TYPE)) {

            if ($stream->test(\Twig_Token::BLOCK_END_TYPE)) {
                throw new \Twig_Error_Syntax(
                    'A lexer is needed for highlighting. ',
                    $stream->getCurrent()->getLine(),
                    $stream->getFilename()
                );
            }

            $lexer = $this->parser->getExpressionParser()->parseExpression();
            if(array_key_exists($lexer->getAttribute('name'), $acceptedLexers)) {
                $lexer->setAttribute(
                    'name',
                    $acceptedLexers[$lexer->getAttribute('name')]
                );
            }
            else {
                if (array_key_exists($lexer->getAttribute('name'), $additionalLexers)) {
                    $lexer->setAttribute('name', $additionalLexers[$lexer->getAttribute('name')]);
                } else {
                    throw new \Twig_Error_Syntax(
                        'Unexpected lexer: ' . $lexer->getAttribute('name'),
                        $stream->getCurrent()->getLine(),
                        $stream->getFilename()
                    );
                }
            }
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        $source = $this->parser->subparse(array($this, 'decideBlockEnd'), true);

        if (!$source instanceof \Twig_Node &&
            !$source instanceof \Twig_Node_Text &&
            !$source instanceof \Twig_Node_Expression
        ) {
            throw new \Twig_Error_Syntax(
                'A message inside a highlight tag must be a simple text.',
                $source->getLine(),
                $stream->getFilename()
            );
        }

        $stream->expect(\Twig_Token::BLOCK_END_TYPE);

        return new HighlightNode($source, $lexer, $lineNo, $this->getTag());
    }

    public function decideBlockEnd(\Twig_Token $token)
    {
        return $token->test(array('endhighlight'));
    }

    /**
     * Gets the tag name associated with this token parser.
     *
     * @return string The tag name
     */
    public function getTag()
    {
        return 'highlight';
    }
}
