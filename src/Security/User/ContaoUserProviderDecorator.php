<?php

namespace HeimrichHannot\Email2UsernameBundle\Security\User;

use Contao\CoreBundle\Security\User\ContaoUserProvider;
use Contao\MemberModel;
use Contao\Model;
use Contao\UserModel;
use Contao\Validator;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ContaoUserProviderDecorator implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        private readonly ContaoUserProvider $inner,
        private readonly string $userTable,
    ) {
        if ('tl_user' !== $userTable && 'tl_member' !== $userTable) {
            throw new \RuntimeException(\sprintf('Unsupported class "%s".', $userTable));
        }
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        try {
            return $this->inner->refreshUser($user);
        } catch (UserNotFoundException $e) {
            return $this->tryLoadByEmail($user->getUserIdentifier(), $e);
        }
    }

    public function supportsClass(string $class): bool
    {
        return $this->inner->supportsClass($class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            return $this->inner->loadUserByIdentifier($identifier);
        } catch (UserNotFoundException $e) {
        }

        return $this->tryLoadByEmail($identifier, $e);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        $this->inner->upgradePassword($user, $newHashedPassword);
    }

    private function tryLoadByEmail(string $identifier, UserNotFoundException|\Exception $e): UserInterface
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

        return $this->inner->loadUserByIdentifier($user->username);
    }
}