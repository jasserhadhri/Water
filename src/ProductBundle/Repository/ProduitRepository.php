<?php


namespace ProductBundle\Repository;

/**
 * PromotionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{

    public function getTopProducts(){


        $query = $this->getEntityManager()->createQuery(
            'SELECT p, COUNT(p.id) as cmd_count
    FROM ProductBundle:Produit p LEFT JOIN ProductBundle:Commande c
    WITH p.id = c.produit GROUP BY p.id ORDER BY cmd_count DESC'
        );


        return $query->getResult();
    }
}