<?php

namespace Vundb\ServiceBundle\Repository;

use Google\Cloud\Firestore\DocumentSnapshot;
use Vundb\FirestoreBundle\Repository\Repository;
use Vundb\ServiceBundle\Entity\User;
use Vundb\ServiceBundle\Hydrator\UserHydrator;

class UserRepository extends Repository
{
    private const COLLECTION = 'users';

    public function __construct(
        private string $database,
        private UserHydrator $hydrator
    ) {
        parent::__construct($database);
    }

    /**
     * @return array<User>
     */
    public function findByUsername(string $username): array
    {
        $documents = $this->client->collection(self::COLLECTION)
            ->where('username', '=', $username)
            ->documents();

        $users = [];
        foreach ($documents as $document) {
            $users[] = $this->hydrator->hydrateDocumentSnapshot($document);
        }

        return $users;
    }

    public function findOneByUsername(string $username): ?User
    {
        $users = $this->findByUsername($username);
        if (empty($users)) {
            return null;
        }

        return $users[0];
    }

    protected function collection(): string
    {
        return self::COLLECTION;
    }

    protected function hydrate(DocumentSnapshot $document): mixed
    {
        return $this->hydrator->hydrateDocumentSnapshot($document);
    }
}
