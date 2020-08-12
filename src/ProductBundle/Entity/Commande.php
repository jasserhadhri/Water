<?php

namespace ProductBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="ProductBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var float
     *
     * @ORM\Column(name="totale", type="float")
     */
    private $totale;

    /**
     * @var int
     *
     * @ORM\Column(name="numcommande", type="integer", unique=true)
     */
    private $numCommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCommande", type="date")
     */
    private $dateCommande;

    /**
     * @var string
     *
     * @ORM\Column(name="methodePaiment", type="string", length=255)
     */
    private $methodePaiment;

    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Promotion")
     * @ORM\JoinColumn(name="idPromotion",referencedColumnName="id")
     */
    private $idPromotion;
    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Produit")
     * @ORM\JoinColumn(name="idProduit",referencedColumnName="id")
     */
    private $idProduit;

    /**
     * @return mixed
     */
    public function getIdPromotion()
    {
        return $this->idPromotion;
    }

    /**
     * @param mixed $idPromotion
     */
    public function setIdPromotion($idPromotion)
    {
        $this->idPromotion = $idPromotion;
    }

    /**
     * @return mixed
     */
    public function getIdProduit()
    {
        return $this->idProduit;
    }

    /**
     * @param mixed $idProduit
     */
    public function setIdProduit($idProduit)
    {
        $this->idProduit = $idProduit;
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return Commande
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set totale
     *
     * @param float $totale
     *
     * @return Commande
     */
    public function setTotale($totale)
    {
        $this->totale = $totale;

        return $this;
    }

    /**
     * Get totale
     *
     * @return float
     */
    public function getTotale()
    {
        return $this->totale;
    }

    /**
     * Set numcommande
     *
     * @param integer $numcommande
     *
     * @return Commande
     */
    public function setNumcommande($numcommande)
    {
        $this->numcommande = $numcommande;

        return $this;
    }

    /**
     * Get numcommande
     *
     * @return int
     */
    public function getNumcommande()
    {
        return $this->numcommande;
    }

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return Commande
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set methodePaiment
     *
     * @param string $methodePaiment
     *
     * @return Commande
     */
    public function setMethodePaiment($methodePaiment)
    {
        $this->methodePaiment = $methodePaiment;

        return $this;
    }

    /**
     * Get methodePaiment
     *
     * @return string
     */
    public function getMethodePaiment()
    {
        return $this->methodePaiment;
    }
}

