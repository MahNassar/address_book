<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * AddressRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AddressRepository extends EntityRepository
{

    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $item = $this->find($id);
        $entityManager->remove($item);
        $entityManager->flush();
        return true;
    }

}