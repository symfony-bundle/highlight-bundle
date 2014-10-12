<?php
/**
 * This file is part of the Highlight Bundle for Symfony.
 *
 * @copyright   Copyright (C) 2014 Hassan Amouhzi. All rights reserved.
 * @license     The MIT License (MIT), see LICENSE.md
 */
 
namespace Anezi\Bundle\HighlightBundle\Composer;

use Composer\Script\CommandEvent;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as SensioScriptHandler;

/**
 * Class ScriptHandler
 *
 * Based on Sensio\Bundle\DistributionBundle\Composer\ScriptHandler
 *
 * @package Anezi\Bundle\HighlightBundle\Composer
 */
class ScriptHandler extends SensioScriptHandler
{
    public static function installExtraAssets(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $consoleDir = self::getConsoleDir($event, 'install assets');

        if (null === $consoleDir) {
            return;
        }

        $webDir = $options['symfony-web-dir'];

        if (!self::hasDirectory($event, 'symfony-web-dir', $webDir, 'install assets')) {
            return;
        }

        static::executeCommand(
            $event,
            $consoleDir,
            'highlight:extra-assets:install ' . escapeshellarg($webDir)
        );
    }
}
