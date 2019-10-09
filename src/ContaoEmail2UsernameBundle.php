<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\Email2UsernameBundle;


use HeimrichHannot\Email2UsernameBundle\DependencyInjection\ContaoEmail2UsernameExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContaoEmail2UsernameBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ContaoEmail2UsernameExtension();
    }
}