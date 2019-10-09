<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\Email2UsernameBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class ContaoEmail2UsernameExtension extends Extension
{
    public function getAlias()
    {
        return 'huh_email2username';
    }


    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);
        $container->setParameter('huh_email2username', $processedConfig);
    }
}