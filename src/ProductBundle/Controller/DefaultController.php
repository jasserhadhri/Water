<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Categorie;
use ProductBundle\Entity\Marque;
use ProductBundle\Entity\Produit;
use ProductBundle\Entity\Promotion;
use ProductBundle\Form\CategorieType;
use ProductBundle\Form\MarqueType;
use ProductBundle\Form\ProduitType;
use ProductBundle\Form\PromotionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Product\Default\index.html.twig');
    }
    public function AjouterCategorieAction(Request $request){
        $categorie = new Categorie();
        $form =$this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
        }
        return $this->render('@Product\Default\AjouterCategorie.html.twig',array("form"=>$form->createView()));
    }
    public function AjouterMarqueAction(Request $request){
        $marque = new Marque();
        $form =$this->createForm(MarqueType::class,$marque);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush();
        }
        return $this->render('@Product\Default\AjouterMarque.html.twig',array("form"=>$form->createView()));
    }
    public function AjouterProduitAction(Request $request){
        $produit = new Produit();
        $form =$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file=$produit->getImage();

            if($file != null)
            {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $path="../web";
                $file->move($path ,$fileName);
                $produit->setImage($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
        }
        return $this->render('@Product\Default\AjouterProduit.html.twig',array("form"=>$form->createView()));
    }
    public function AjouterPromotionAction(Request $request){
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class,$promotion);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();
        }
        return $this->render('@Product\Default\AjouterPromotion.html.twig',array("form"=>$form->createView()));
    }
    public function AfficherProduitsAction(){
        $produits = $this->getDoctrine()->getRepository(Produit::class)
            ->findAll();
        return $this->render('@Product\Default\AfficherProduit.html.twig', array('produits' => $produits));

    }
}