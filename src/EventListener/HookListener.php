<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Database;
use Contao\Input;
use Contao\System;
use Contao\Validator;

class HookListener
{
    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    public function __construct(array $bundleConfig, ContaoFrameworkInterface $framework)
    {
        if (isset($bundleConfig['user']) && true !== $bundleConfig['user']) {
            $this->enabled = false;
        }

        $this->framework = $framework;
    }

    /**
     * This Hook provides case-insensitive contao-login by email usernames.
     *
     * RFC 5321, section-2.3.11 says that email addresses should be treated as case-insensitive
     *
     * @param $strUser
     * @param $strPassword
     * @param $strTable
     *
     * @return bool
     */
    public function onImportUser($strUser, $strPassword, $strTable)
    {
        if (!$this->enabled || !$this->framework->getAdapter(Validator::class)->isEmail($strUser)) {
            return false;
        }

        switch ($strTable) {
            case 'tl_member':
                $objMember = $this->framework->createInstance(Database::class)->prepare('SELECT * from tl_member WHERE lower(username) = ?')->limit(1)->execute($strUser);

                if ($objMember->numRows > 0) {
                    // set post user name to the users username
                    $this->framework->getAdapter(Input::class)->setPost('username', $objMember->username);

                    return true;
                }

                break;

            case
            'tl_user':
                $objUser = $this->framework->createInstance(Database::class)->prepare('SELECT * from tl_user WHERE lower(username) = ?')->limit(1)->execute($strUser);

                if ($objUser->numRows > 0) {
                    // set post user name to the users username
                    $this->framework->getAdapter(Input::class)->setPost('username', $objUser->username);

                    return true;
                }

                break;
        }

        return false;
    }

    public function onCreateNewUser($id, $data, $module)
    {
        if (!System::getContainer()->get('huh.utils.container')->isFrontend()) {
            return;
        }

        if (!$this->enabled || !$module->reg_allowLogin || null === ($member = System::getContainer()->get('huh.utils.model')->findModelInstanceByPk('tl_member', $id))) {
            return;
        }

        if (!$this->framework->getAdapter(Validator::class)->isEmail($member->email)) {
            return;
        }

        $member->username = strtolower($member->email);
        $member->save();
    }
}
