<?php

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Contao\UserModel;

class BackendUserListener extends AbstractUserListener
{
    public function __construct(
        private readonly array $config,
    ) {
        $this->setOverride($this->config['override'] ?? true);
    }

    #[AsCallback(table: 'tl_user', target: 'config.onload')]
    public function onLoad(?DataContainer $dc = null): void
    {
        if (!$this->override) {
            return;
        }

        $field = &$GLOBALS['TL_DCA']['tl_user']['fields']['username'];
        $field['eval']['rgxp'] = 'email';
        $field['eval']['readonly'] = true;
        $field['eval']['mandatory'] = false;
    }

    #[AsCallback(table: 'tl_user', target: 'config.onsubmit')]
    public function onSubmit(DataContainer $dc): void
    {
        if (!$dc->id) {
            return;
        }

        $this->updateUserIfNeeded((int) $dc->id);
    }

    protected function modelClass(): string
    {
        return UserModel::class;
    }
}
