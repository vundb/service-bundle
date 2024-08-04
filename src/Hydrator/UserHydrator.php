<?php

namespace Vundb\ServiceBundle\Hydrator;

use Google\Cloud\Firestore\DocumentSnapshot;
use Vundb\ServiceBundle\Entity\User;

class UserHydrator implements HydratorInterface
{
    public function hydrateDocumentSnapshot(DocumentSnapshot $document)
    {
        return (new User())
            ->setId($document->id())
            ->setUsername($document->get('username'))
            ->setRoles($document->get('roles'))
            ->setTokens($document->get('tokens'))
            ->setCreated(new \DateTime($document->get('created')))
            ->setConfig($document->get('config'));
    }
}