<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\Helper;

use Contao\MemberModel;
use Contao\Model;
use Contao\System;
use Contao\UserModel;
use HeimrichHannot\UtilsBundle\Database\DatabaseUtil;

class UsernameHelper
{
    /**
     * Set username for user or member model.
     */
    public static function setUsernameFromEmail(Model $user, bool $disableOverrideExistingUsernames = false)
    {
        if (!($user instanceof MemberModel) && !($user instanceof UserModel) && !(class_exists('Terminal42\DcMultilingualBundle\Model\Multilingual') && is_subclass_of($user, 'Terminal42\DcMultilingualBundle\Model\Multilingual'))) {
            return;
        }

        if ($disableOverrideExistingUsernames && !empty($user->username)) {
            return;
        }

        if (!$user->email) {
            return;
        }

        $t = $user::getTable();

        System::getContainer()->get(DatabaseUtil::class)->update(
            $user::getTable(), [
                'username' => strtolower($user->email),
            ], "$t.id=?", [$user->id]
        );
    }
}
