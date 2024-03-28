<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle;

use HeimrichHannot\Email2UsernameBundle\DependencyInjection\ContaoEmail2UsernameExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoEmail2UsernameBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new ContaoEmail2UsernameExtension();
    }
}