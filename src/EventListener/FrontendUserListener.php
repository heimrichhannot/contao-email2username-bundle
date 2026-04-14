<?php

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\DataContainer;
use Contao\FrontendUser;
use Contao\Input;
use Contao\MemberModel;
use Contao\Module;
use Contao\ModuleModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class FrontendUserListener extends AbstractUserListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly ScopeMatcher $scopeMatcher,
        private readonly TranslatorInterface $translator,
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
        if (!empty($userData['username'])) {
            return;
        }

        $this->updateUserIfNeeded($userId);
    }

    #[AsCallback(table: 'tl_member', target: 'config.onload')]
    public function onLoad(?DataContainer $dc = null): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$this->scopeMatcher->isBackendRequest($request)) {
            return;
        }

        $field = &$GLOBALS['TL_DCA']['tl_member']['fields']['username'];
        $field['eval']['mandatory'] = false;
        if (!$this->override) {
            return;
        }
        $field['eval']['readonly'] = true;
    }

    #[AsCallback(table: 'tl_member', target: 'fields.email.save')]
    public function onFieldsEmailSave(mixed $value, DataContainer|FrontendUser|null $dc): mixed
    {
        if (null !== $dc) {
            return $value;
        }

        if (!Input::post('username')) {
            $value = \mb_strtolower($value);
            if (MemberModel::findByEmail($value)) {
                throw new \Exception($this->translator->trans('error.duplicate_email', domain: 'huh_e2u'));
            }
        }

        return $value;
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
