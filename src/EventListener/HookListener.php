<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Contao\Input;
use Contao\Validator;

class HookListener
{
    private bool $enabledBE = true;
    private bool $enabledFE = true;
    private ContaoFramework $framework;

    public function __construct(array $bundleConfig, ContaoFramework $framework)
    {
        if (isset($bundleConfig['user']) && true !== $bundleConfig['user']) {
            $this->enabledBE = false;
        }
        if (isset($bundleConfig['member']) && true !== $bundleConfig['member']) {
            $this->enabledFE = false;
        }

        $this->framework = $framework;
    }

    /**
     * This Hook provides case-insensitive contao-login by email usernames.
     *
     * RFC 5321, section-2.3.11 says that email addresses should be treated as case-insensitive
     *
     * @return bool
     */
    public function onImportUser($strUser, $strPassword, $strTable)
    {
        if (!$this->framework->getAdapter(Validator::class)->isEmail($strUser)) {
            return false;
        }

        switch ($strTable) {
            case 'tl_member':
                if (!$this->enabledFE) {
                    return false;
                }
                $objMember = $this->framework->createInstance(Database::class)
                    ->prepare('SELECT * from tl_member WHERE lower(username) = ?')
                    ->limit(1)
                    ->execute(strtolower($strUser));

                if ($objMember->numRows > 0) {
                    // set post user name to the users username
                    $this->framework->getAdapter(Input::class)->setPost('username', $objMember->username);

                    return true;
                }

                break;

            case 'tl_user':
                if (!$this->enabledBE) {
                    return false;
                }
                $objUser = $this->framework->createInstance(Database::class)
                    ->prepare('SELECT * from tl_user WHERE lower(username) = ?')
                    ->limit(1)
                    ->execute(strtolower($strUser));

                if ($objUser->numRows > 0) {
                    // set post user name to the users username
                    $this->framework->getAdapter(Input::class)->setPost('username', $objUser->username);

                    return true;
                }

                break;
        }

        return false;
    }
}
