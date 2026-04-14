<?php

namespace HeimrichHannot\Email2UsernameBundle\Security\User;

use Contao\BackendUser;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Security\User\ContaoUserProvider;
use Contao\FrontendUser;
use Contao\MemberModel;
use Contao\Model;
use Contao\User;
use Contao\UserModel;
use Contao\Validator;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class ContaoUserProviderDecorator extends ContaoUserProvider
{
    private readonly string $userTable;

    public function __construct(
        ContaoFramework $framework,
        private readonly string $userClass,
    ) {
        $this->userTable = match ($userClass) {
            BackendUser::class => 'tl_user',
            FrontendUser::class => 'tl_member',
            default => throw new \RuntimeException(\sprintf('Unsupported class "%s".', $userClass)),
        };

        parent::__construct($framework, $this->userClass);
    }

    public function refreshUser(UserInterface $user): User
    {
        try {
            return parent::refreshUser($user);
        } catch (UserNotFoundException $e) {
            return $this->tryLoadByEmail($user->getUserIdentifier(), $e);
        }
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        try {
            return parent::loadUserByIdentifier($identifier);
        } catch (UserNotFoundException $e) {
        }

        return $this->tryLoadByEmail($identifier, $e);
    }

    private function tryLoadByEmail(string $identifier, UserNotFoundException|\Exception $e): User
    {
        if (!Validator::isEmail($identifier)) {
            throw $e;
        }

        /** @var class-string<UserModel|MemberModel> $model */
        $model = Model::getClassFromTable($this->userTable);

        $user = $model::findByEmail($identifier);
        if (!$user) {
            throw $e;
        }

        return parent::loadUserByIdentifier($user->username);
    }
}
