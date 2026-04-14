<?php

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\Model;

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

        $user->username = $user->email;
        $user->save();
    }
}