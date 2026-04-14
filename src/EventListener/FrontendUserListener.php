<?php

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\DataContainer;
use Contao\FrontendUser;
use Contao\MemberModel;
use Contao\Module;
use Symfony\Component\HttpFoundation\RequestStack;

class FrontendUserListener extends AbstractUserListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly array $config,
    ) {
        $this->setOverride($this->config['override'] ?? true);
    }

    #[AsHook('loadDataContainer')]
    public function onLoadDataContainer(string $table): void
    {
        if ('tl_member' !== $table || !$this->override) {
            return;
        }

        $GLOBALS['TL_DCA']['tl_member']['fields']['username']['eval']['feEditable'] = false;
    }

    #[AsHook('createNewUser')]
    public function onCreateNewUser(int $userId, array $userData, Module $module): void
    {
        $this->updateUserIfNeeded($userId);
    }

    #[AsCallback(table: 'tl_member', target: 'config.onload')]
    public function onLoad(?DataContainer $dc = null): void
    {
        $field = &$GLOBALS['TL_DCA']['tl_member']['fields']['username'];
        $field['eval']['mandatory'] = false;

        if (!$this->override) {
            return;
        }

        $field['eval']['rgxp'] = 'email';

        $request = $this->requestStack->getCurrentRequest();
        if ($request && $this->scopeMatcher->isBackendRequest($request)) {
            $field['eval']['readonly'] = true;
        }
    }

    #[AsCallback(table: 'tl_member', target: 'config.onsubmit')]
    public function onSubmit(DataContainer|FrontendUser $dc): void
    {
        if (!($dc instanceof DataContainer) || !$dc->id) {
            return;
        }

        $this->updateUserIfNeeded((int) $dc->id);
    }

    protected function modelClass(): string
    {
        return MemberModel::class;
    }
}
