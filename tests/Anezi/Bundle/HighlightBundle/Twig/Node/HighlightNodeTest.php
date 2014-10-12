<?php

namespace Anezi\Bundle\BootstrapBundle\Tests\Twig\Node;

use Anezi\Bundle\BootstrapBundle\Twig\Node\HighlightNode;

class HighlightNodeTest extends \PHPUnit_Framework_TestCase
{
    public function testCompile()
    {
        $body = new \Twig_Node_Text('<html><head></head><body class="body"></body></html>', 0);
        $node = new HighlightNode($body, null, null, 'html');

        $env = new \Twig_Environment(null, array('strict_variables' => true));
        $compiler = new \Twig_Compiler($env);

        $expected = <<<TEXT
echo '<div class="zero-clipboard"><span class="btn-clipboard btn-clipboard-hover">Copy</span></div>';
\$result = \PHPygments\PHPygments::render("". "<html><head></head><body class=\"body\"></body></html>"
, 'html');
echo preg_replace('#^<div class="highlighted-source default (.*)"><pre>(.*)</pre></div>$#s', '<div class="highlight"><pre><code class="$1">$2</code></pre></div>', \$result["code"]);
TEXT;

        $this->assertEquals(
            $expected,
            trim($compiler->compile($node)->getSource())
        );
    }
}
