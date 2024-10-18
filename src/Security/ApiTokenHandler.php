<?php

namespace Vundb\ServiceBundle\Security;

use Google\Cloud\Firestore\Filter;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Vundb\ServiceBundle\Repository\UserRepository;

class ApiTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $users = $this->userRepository->findBy(Filter::field('tokens', 'array-contains', $accessToken));
        if (null === $accessToken || empty($users)) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($users[0]->getUserIdentifier());
    }
}
