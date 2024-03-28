<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\Module;
use HeimrichHannot\Email2UsernameBundle\Helper\UsernameHelper;
use HeimrichHannot\UtilsBundle\Util\Utils;

class CreateNewUserListener
{
    private bool $enabled = true;
    private Utils $utils;


    /**
     * CreateNewUserListener constructor.
     */
    public function __construct(array $bundleConfig, Utils $utils)
    {
        if (isset($bundleConfig['member']) && true !== $bundleConfig['member']) {
            $this->enabled = false;
        }
        $this->utils = $utils;
    }

    /**
     * @param Module $module
     */
    public function __invoke(int $userId, array $userData, $module)
    {
        if (!$this->enabled || !$module->reg_allowLogin) {
            return;
        }

        $member = $this->utils->model()->findModelInstanceByPk('tl_member', $userId);

        if (null === $member) {
            return;
        }

        UsernameHelper::setUsernameFromEmail($member);
    }
}