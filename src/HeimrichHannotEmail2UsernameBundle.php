<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle;

use Contao\CoreBundle\Routing\ScopeMatcher;
use HeimrichHannot\Email2UsernameBundle\EventListener\BackendUserListener;
use HeimrichHannot\Email2UsernameBundle\EventListener\FrontendUserListener;
use HeimrichHannot\Email2UsernameBundle\Security\User\ContaoUserProviderDecorator;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Contracts\Translation\TranslatorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class HeimrichHannotEmail2UsernameBundle extends AbstractBundle
{
    protected string $extensionAlias = 'huh_email2username';

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        if (true === ($config['member']['enable'] ?? true)) {
            $container->services()->set(FrontendUserListener::class)
                ->autoconfigure()
                ->args([
                    service(RequestStack::class),
                    service(ScopeMatcher::class),
                    service(TranslatorInterface::class),
                    $config['member'] ?? [],
                ]);
            $container->services()->set('e2u.decorator.frontend_user_provider', ContaoUserProviderDecorator::class)
                ->decorate('contao.security.frontend_user_provider')
                ->args([
                    service('.inner'),
                    'tl_member',
                ]);
        }

        if (true === ($config['user']['enable'] ?? true)) {
            $container->services()->set(BackendUserListener::class)
                ->autoconfigure()
                ->args([
                    $config['user'] ?? [],
                ]);
            $container->services()->set('e2u.decorator.backend_user_provider', ContaoUserProviderDecorator::class)
                ->decorate('contao.security.backend_user_provider')
                ->args([
                    service('.inner'),
                    'tl_user',
                ]);
        }

        $container->parameters()->set($this->extensionAlias, $config);
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->arrayNode('member')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifTrue(fn ($v) => !\is_array($v))
                        ->thenInvalid('Invalid type for "member": expected an array. The config format has changed in V2.')
                    ->end()
                    ->children()
                        ->booleanNode('enable')->info('Enable support for frontend member.')->defaultTrue()->end()
                        ->booleanNode('override')->info('Allow override existing usernames.')->defaultTrue()->end()
                    ->end()
                ->end()
                ->arrayNode('user')
                    ->addDefaultsIfNotSet()
                    ->beforeNormalization()
                        ->ifTrue(fn ($v) => !\is_array($v))
                        ->thenInvalid('Invalid type for "user": expected an array. The config format has changed in V2.')
                    ->end()
                    ->children()
                        ->booleanNode('enable')->info('Enable support for backend user.')->defaultTrue()->end()
                        ->booleanNode('override')->info('Allow override existing usernames.')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end();
    }
}
