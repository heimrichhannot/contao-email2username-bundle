<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\Helper;

use Contao\MemberModel;
use Contao\Model;
use Contao\UserModel;

class UsernameHelper
{
    /**
     * Set username for user or member model.
     */
    public static function setUsernameFromEmail(Model $user, bool $disableOverrideExistingUsernames = false)
    {
        if (!($user instanceof MemberModel) && !($user instanceof UserModel)) {
            return;
        }

        if ($disableOverrideExistingUsernames && !empty($user->username)) {
            return;
        }

        if (!$user->email) {
            return;
        }

        $user->username = strtolower($user->email);
        $user->save();
    }
}
