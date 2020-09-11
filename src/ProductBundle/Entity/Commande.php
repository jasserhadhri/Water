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
     * @ORM\Column(name="numcommande", type="string")
     */
    private $numCommande;
    /**
     * @var int
     *
     * @ORM\Column(name="enable", type="integer")
     */
    private $enable;
    /**
     * @var int
     *
     * @ORM\Column(name="emailsend", type="integer")
     */
    private $emailsend;

    /**
     * @return int
     */
    public function getEmailsend()
    {
        return $this->emailsend;
    }

    /**
     * @param int $emailsend
     */
    public function setEmailsend($emailsend)
    {
        $this->emailsend = $emailsend;
    }

    /**
     * @return int
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param int $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }

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
    private $promotion;
    /**
     * @ORM\ManyToOne(targetEntity="ProductBundle\Entity\Produit")
     * @ORM\JoinColumn(name="idProduit",referencedColumnName="id")
     */
    private $produit;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user",referencedColumnName="id")
     */
    private $user;


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }


    /**
     * @return mixed
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * @param mixed $promotion
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * @return mixed
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param mixed $produit
     */
    public function setProduit($produit)
    {
        $this->produit = $produit;
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
     * @return int
     */
    public function getNumCommande()
    {
        return $this->numCommande;
    }

    /**
     * @param int $numCommande
     */
    public function setNumCommande($numCommande)
    {
        $this->numCommande = $numCommande;
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

