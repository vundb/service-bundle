<?php

namespace Vundb\ServiceBundle\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vundb\FirestoreBundle\Entity\Entity;

/**
 * @extends Entity<User>
 * @method string getUsername()
 * @method self setUsername(string $value)
 * @method ?\DateTime getCreated()
 * @method self setCreated(\DateTime $value)
 * @method self setRoles(array $value)
 * @method array getTokens()
 * @method self setTokens(array $value)
 * @method array getConfig()
 * @method self setConfig(array $value)
 */
class User extends Entity implements UserInterface, PasswordAuthenticatedUserInterface
{
    protected string $username = '';

    protected array $roles = [];

    protected array $tokens = [];

    protected array $config = [];

    protected ?\DateTime $created = null;

    public function getRoles(): array
    {
        return array_unique(
            array_merge(
                ['ROLE_USER'],
                $this->roles
            )
        );
    }

    public function addToken(string $token): self
    {
        $this->tokens = array_unique(
            array_merge($this->tokens, [$token])
        );
        return $this;
    }

    public function getPassword(): string
    {
        return '';
    }

    public function eraseCredentials(): void
    {
        // required by interface but not used
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}
