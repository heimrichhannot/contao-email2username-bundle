<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\Email2UsernameBundle\DataContainer;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\DataContainer;
use Contao\MemberModel;
use Contao\Model;

class MemberContainer
{
    /**
     * @var bool
     */
    private $enabled = true;
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * MemberContainer constructor.
     */
    public function __construct(array $bundleConfig, ContaoFrameworkInterface $framework)
    {
        if (isset($bundleConfig['member']) && true !== $bundleConfig['member']) {
            $this->enabled = false;
        }
        $this->framework = $framework;
    }

    public function onLoad()
    {
        if ($this->enabled) {
            $GLOBALS['TL_DCA']['tl_member']['fields']['username']['eval']['disabled'] = true;
            $GLOBALS['TL_DCA']['tl_member']['fields']['username']['eval']['mandatory'] = false;
        }
    }

    /**
     * onSubmit callback.
     *
     * @param DataContainer $dc
     */
    public function onSubmit($dc)
    {
        if (!$this->enabled) {
            return;
        }
        $this->setMembernameFromEmail($dc);
    }

    /**
     * OnSubmit callback.
     *
     * @param DataContainer $dc
     */
    protected function setMembernameFromEmail($dc)
    {
        /** @var MemberModel $member */
        if (null === $member && null === ($member = $this->framework->getAdapter(MemberModel::class)->findByPk($dc->id))) {
            return;
        }

        if (null !== $dc->activeRecord) {
            $email = $dc->activeRecord->email;
        } else {
            if ($member instanceof Model) {
                $member->refresh();
            }

            $email = $member->email;
        }

        if (!$email) {
            return;
        }

        $member->username = strtolower($email);
        $member->save();
    }
}
