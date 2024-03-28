<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\Helper;

use Contao\Database;
use Contao\MemberModel;
use Contao\Model;
use Contao\System;
use Contao\UserModel;

class UsernameHelper
{
    /**
     * Set username for user or member model.
     */
    public static function setUsernameFromEmail(Model $user, bool $disableOverrideExistingUsernames = false): void
    {
        if (!($user instanceof MemberModel)
            && !($user instanceof UserModel)
            && !(class_exists('Terminal42\DcMultilingualBundle\Model\Multilingual')
                && is_subclass_of($user, 'Terminal42\DcMultilingualBundle\Model\Multilingual')))
        {
            return;
        }

        if ($disableOverrideExistingUsernames && !empty($user->username)) {
            return;
        }

        if (!$user->email) {
            return;
        }

        $t = $user::getTable();

        System::getContainer()->get('contao.framework')->createInstance(Database::class)
            ->prepare("UPDATE $t SET username=? WHERE id=?")
            ->execute(strtolower($user->email), $user->id);
    }
}