<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\Module;
use HeimrichHannot\Email2UsernameBundle\Helper\UsernameHelper;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;

class CreateNewUserListener
{
    /**
     * @var bool
     */
    private $enabled = true;
    /**
     * @var ModelUtil
     */
    private $modelUtil;

    /**
     * CreateNewUserListener constructor.
     */
    public function __construct(array $bundleConfig, ModelUtil $modelUtil)
    {
        if (isset($bundleConfig['member']) && true !== $bundleConfig['member']) {
            $this->enabled = false;
        }
        $this->modelUtil = $modelUtil;
    }

    /**
     * @param Module $module
     */
    public function __invoke(int $userId, array $userData, $module)
    {
        if (!$this->enabled || !$module->reg_allowLogin || null === ($member = $this->modelUtil->findModelInstanceByPk('tl_member', $userId))) {
            return;
        }

        UsernameHelper::setUsernameFromEmail($member);
    }
}
