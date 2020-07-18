<?php

namespace HuntersKingdomBundle\Repository;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * productRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class productRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllProductsByCommande($id)
    {




        $conn = $this->getEntityManager()
            ->getConnection();
        $sql = 'select p.id, reference, libelle, description, type, categorie, prix, image, date from product p inner join commande_product c on p.id = c.product_id and c.commande_id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute(array('id' => $id));
        return $stmt->fetchAll();

    }
}
