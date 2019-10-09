<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @author  Thomas Körner <t.koerner@heimrich-hannot.de>
 * @license http://www.gnu.org/licences/lgpl-3.0.html LGPL
 */


namespace HeimrichHannot\Email2UsernameBundle\DataContainer;


use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\DataContainer;
use Contao\DC_Table;
use Contao\Model;
use Contao\UserModel;

class UserContainer
{
    /**
     * @var bool
     */
    private $enabled = true;

    /**
     * @var ContaoFrameworkInterface
     */
    protected $framework;

    /**
     * UserContainer constructor.
     */
    public function __construct(array $bundleConfig, ContaoFrameworkInterface $framework)
    {
        if (isset($bundleConfig['user']) && true !== $bundleConfig['user']) {
            $this->enabled = false;
        }
        $this->framework = $framework;
    }

    public function onLoad(DC_Table $dcTable)
    {
        if (true === $this->enabled) {
            $GLOBALS['TL_DCA']['tl_user']['fields']['username']['eval']['disabled']  = true;
            $GLOBALS['TL_DCA']['tl_user']['fields']['username']['eval']['mandatory'] = false;
        }
    }

    public function onSubmit(DataContainer $dc)
    {
        if (!$this->enabled) {
            return;
        }
        $this->setUsernameFromEmail($dc);
    }



    /**
     * Onsubmit callback
     *
     * @param DataContainer $dc
     */
    protected function setUsernameFromEmail(DataContainer $dc)
    {
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