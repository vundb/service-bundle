<?php

namespace Vundb\ServiceBundle\Hydrator;

use Google\Cloud\Firestore\DocumentSnapshot;

interface HydratorInterface
{
    public function hydrateDocumentSnapshot(DocumentSnapshot $document);
}
