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
use Contao\Database;
use Contao\Input;
use Contao\Validator;

class HookListener
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
     * This Hook provides case-insensitive contao-login by email usernames
     *
     * RFC 5321, section-2.3.11 says that email addresses should be treated as case-insensitive
     *
     * @param $strUser
     * @param $strPassword
     * @param $strTable
     *
     * @return bool
     */
    public function importUserHook($strUser, $strPassword, $strTable)
    {
        if (!$this->framework->getAdapter(Validator::class)->isEmail($strUser))
        {
            return false;
        }

        switch ($strTable)
        {
            case 'tl_member':
                $objMember = $this->framework->createInstance(Database::class)->prepare('SELECT * from tl_member WHERE lower(username) = ?')->limit(1)->execute($strUser);

                if ($objMember->numRows > 0)
                {
                    // set post user name to the users username
                    $this->framework->getAdapter(Input::class)->setPost('username', $objMember->username);

                    return true;
                }
                break;
            case
            'tl_user':
                $objUser = $this->framework->createInstance(Database::class)->prepare('SELECT * from tl_user WHERE lower(username) = ?')->limit(1)->execute($strUser);

                if ($objUser->numRows > 0)
                {
                    // set post user name to the users username
                    $this->framework->getAdapter(Input::class)->setPost('username', $objUser->username);

                    return true;
                }
                break;
        }

        return false;
    }
}