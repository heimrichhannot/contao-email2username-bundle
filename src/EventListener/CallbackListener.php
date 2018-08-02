<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\Email2UsernameBundle\EventListener;


use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\DataContainer;
use Contao\MemberModel;
use Contao\UserModel;

class CallbackListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * OnSubmit callback
     *
     * @param DataContainer $objDc
     */
    public function setMembernameFromEmail($objDc)
    {
        /** @var MemberModel $member */
        if (($member = $this->framework->getAdapter(MemberModel::class)->findByPk($objDc->id)) === null || !$member->email)
            return;

        $member->refresh();

        $member->username           = $member->email;
        $objDc->username = $member->email;

        $member->save();
    }

    /**
     * Onsubmit callback
     *
     * @param DataContainer $objDc
     */
    public function setUsernameFromEmail(DataContainer $objDc)
    {
        /** @var UserModel $member */
        if (($member = $this->framework->getAdapter(UserModel::class)->findByPk($objDc->id)) === null || !$member->email)
            return;

        $member->refresh();

        $member->username           = $member->email;
        $objDc->activeRecord->username = $member->email;

        $member->save();
    }

}
