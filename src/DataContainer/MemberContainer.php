<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\DataContainer;

use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Input;
use HeimrichHannot\Email2UsernameBundle\Helper\UsernameHelper;
use HeimrichHannot\UtilsBundle\Util\Utils;

class MemberContainer
{
    private bool $enabled = true;
    private bool $disableOverrideExistingUsernames = false;
    private Utils $utils;

    public function __construct(array $bundleConfig, Utils $utils)
    {
        if (isset($bundleConfig['member']) && true !== $bundleConfig['member']) {
            $this->enabled = false;
        }

        if (isset($bundleConfig['disable_override_existing_usernames']) && true === $bundleConfig['disable_override_existing_usernames']) {
            $this->disableOverrideExistingUsernames = true;
        }

        $this->utils = $utils;
    }

    /**
     * @param DC_Table $dc
     */
    public function onLoad($dc = null): void
    {
        if ($this->enabled) {
            $GLOBALS['TL_DCA']['tl_member']['fields']['username']['eval']['mandatory'] = false;

            if (!$this->disableOverrideExistingUsernames) {
                $GLOBALS['TL_DCA']['tl_member']['fields']['username']['eval']['disabled'] = true;
            }
        }

        if (0 !== \func_num_args()
            || null !== Input::post('username')
            || 0 !== strpos((string) Input::post('FORM_SUBMIT'), 'tl_registration')) {
            return;
        }

        Input::setPost('username', Input::post('email'));
    }

    /**
     * onSubmit callback.
     *
     * @param DataContainer $dc
     */
    public function onSubmit($dc): void
    {
        if (!$this->enabled) {
            return;
        }
        $member = $this->utils->model()->findModelInstanceByPk('tl_member', $dc->id);

        if (!$member) {
            return;
        }
        UsernameHelper::setUsernameFromEmail($member, $this->disableOverrideExistingUsernames);
    }
}
