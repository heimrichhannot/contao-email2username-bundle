<?php

/*
 * Copyright (c) 2022 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('huh_email2username');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('user')->defaultTrue()->info('Enable support for backend user.')->end()
                ->booleanNode('member')->defaultTrue()->info('Enable support for frontend member.')->end()
                ->booleanNode('disable_override_existing_usernames')->defaultFalse()->info('Disable overriding existing usernames.')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
