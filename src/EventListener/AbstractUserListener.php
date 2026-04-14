<?php

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\MemberModel;
use Contao\Model;
use Contao\UserModel;

abstract class AbstractUserListener
{
    protected bool $override;

    /**
     * @return class-string<Model>
     */
    abstract protected function modelClass(): string;

    protected function setOverride(bool $override): void
    {
        $this->override = $override;
    }

    protected function updateUserIfNeeded(int $id): void
    {
        /** @var UserModel|MemberModel|null $user */
        $user = $this->modelClass()::findByPk($id);
        if (!$user) {
            return;
        }
        $user->refresh();

        $force = '' === $user->username;

        if (!$force && !$this->override) {
            return;
        }

        if ($user->username === $user->email) {
            return;
        }

        $user->username = \mb_strtolower($user->email);
        $user->save();
    }
}
