<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\DataContainer;

use Contao\DataContainer;
use Contao\DC_Table;
use HeimrichHannot\Email2UsernameBundle\Helper\UsernameHelper;
use HeimrichHannot\UtilsBundle\Model\ModelUtil;

class UserContainer
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
     * @var bool
     */
    private $disableOverrideExistingUsernames = false;

    /**
     * UserContainer constructor.
     */
    public function __construct(array $bundleConfig, ModelUtil $modelUtil)
    {
        if (isset($bundleConfig['user']) && true !== $bundleConfig['user']) {
            $this->enabled = false;
        }

        if (isset($bundleConfig['disable_override_existing_usernames']) && true === $bundleConfig['disable_override_existing_usernames']) {
            $this->disableOverrideExistingUsernames = true;
        }
        $this->modelUtil = $modelUtil;
    }

    public function onLoad(DC_Table $dcTable)
    {
        if (true === $this->enabled) {
            $GLOBALS['TL_DCA']['tl_user']['fields']['username']['eval']['mandatory'] = false;

            if (!$this->disableOverrideExistingUsernames) {
                $GLOBALS['TL_DCA']['tl_user']['fields']['username']['eval']['disabled'] = true;
            }
        }
    }

    public function onSubmit(DataContainer $dc)
    {
        if (!$this->enabled) {
            return;
        }
        $user = $this->modelUtil->findModelInstanceByPk('tl_user', $dc->id);

        if (!$user) {
            return;
        }
        UsernameHelper::setUsernameFromEmail($user, $this->disableOverrideExistingUsernames);
    }
}
