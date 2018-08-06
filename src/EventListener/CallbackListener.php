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


use Contao\Config;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\DataContainer;
use Contao\MemberModel;
use Contao\Model;
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

    public function modifyDca(string $table, array &$dca)
    {
        switch ($table)
        {
            case 'tl_member':
                if (Config::get('e2uMembers'))
                {
                    $dca['fields']['username']['eval']['disabled']  = true;
                    $dca['fields']['username']['eval']['mandatory'] = false;
                }
                break;
            case 'tl_user':
                if (Config::get('e2uUsers'))
                {
                    $dca['fields']['username']['eval']['disabled']  = true;
                    $dca['fields']['username']['eval']['mandatory'] = false;
                }
                break;
        }
    }

    /**
     * OnSubmit callback
     *
     * @param DataContainer $dc
     */
    public function setMembernameFromEmail($dc)
    {
        if (!Config::get('e2uMembers'))
        {
            return;
        }

        /** @var MemberModel $member */
        if (null === $member && ($member = $this->framework->getAdapter(MemberModel::class)->findByPk($dc->id)) === null) {
            return;
        }

        if (null !== $dc->activeRecord)
        {
            $email = $dc->activeRecord->email;
        }
        else
        {
            if ($member instanceof Model)
            {
                $member->refresh();
            }

            $email = $member->email;
        }

        if (!$email)
        {
            return;
        }

        $member->username = strtolower($email);
        $member->save();
    }

    /**
     * Onsubmit callback
     *
     * @param DataContainer $dc
     */
    public function setUsernameFromEmail(DataContainer $dc)
    {
        if (!Config::get('e2uUsers'))
        {
            return;
        }

        /** @var UserModel $user */
        if (null === $user && ($user = $this->framework->getAdapter(UserModel::class)->findByPk($dc->id)) === null) {
            return;
        }

        if (null !== $dc->activeRecord)
        {
            $email = $dc->activeRecord->email;
        }
        else
        {
            if ($user instanceof Model)
            {
                $user->refresh();
            }

            $email = $user->email;
        }

        if (!$email)
        {
            return;
        }

        $user->username = strtolower($email);
        $user->save();
    }

}
