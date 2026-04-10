<?php

namespace HeimrichHannot\Email2UsernameBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\DataContainer;
use Contao\MemberModel;
use Contao\Module;
use HeimrichHannot\UtilsBundle\Util\Utils;

class FrontendUserListener
{
    public function __construct(
        private readonly Utils $utils
    ) {}

    #[AsHook('loadDataContainer')]
    public function onLoadDataContainer(string $table): void
    {
        if ('tl_member' !== $table) {
            return;
        }

        $GLOBALS['TL_DCA']['tl_member']['fields']['username']['eval']['feEditable'] = false;
    }

    #[AsCallback(table: 'tl_content', target: 'config.onload')]
    public function onLoad(DataContainer|null $dc = null): void
    {
        $field = &$GLOBALS['TL_DCA']['tl_member']['fields']['username'];
        $field['eval']['mandatory'] = false;
        $field['eval']['rgxp'] = 'email';

        if (!$this->utils->container()->isBackend()) {
            // disable_override_existing_usernames
            $field['eval']['disabled'] = true;
        }
    }

    #[AsHook('createNewUser')]
    public function onCreateNewUser(int $userId, array $userData, Module $module): void
    {
        $member = MemberModel::findByPk($userId);
        if (!$member) {
            return;
        }

        $member->username = $member->email;
        $member->save();
    }
}