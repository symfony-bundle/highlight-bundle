<?php

namespace Anezi\Bundle\HighlightBundle\Controller;

use Anezi\Bundle\BootstrapBundle\Model\Page;
use Anezi\Bundle\BootstrapBundle\Model\Site;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;
use Pygments\Pygmentize;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class DocsController extends Controller
{
    public function indexAction()
    {
        $lexers = Pygmentize::lexers();

        $lexers['objcpp'] = $lexers['objc++'];
        unset($lexers['objc++']);
        unset($lexers['obj-c++']);

        $lexers['objectivecpp'] = $lexers['objectivec++'];
        unset($lexers['objective-c++']);

        foreach($lexers as $k=>$v) {
            unset($lexers[$k]);
            $k = str_replace('-','',$k);
            $k = str_replace('+','',$k);
            $k = str_replace(' ','',$k);
            $lexers[$k] = $v;
        }

        unset($lexers['antlrc#']);

        unset($lexers['antlr-c#']);
        unset($lexers['c++']);
        unset($lexers['c++-objdumb']);
        unset($lexers['c#']);

        ksort($lexers);

        return $this->render(
            'HighlightBundle:docs:index.html.twig',
            array(
                'lexers' => $lexers
            )
        );
    }
}
