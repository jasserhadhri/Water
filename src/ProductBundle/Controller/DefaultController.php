<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Categorie;
use ProductBundle\Entity\ImageAccueil;
use ProductBundle\Entity\Marque;
use ProductBundle\Entity\Produit;
use ProductBundle\Entity\Promotion;
use ProductBundle\Form\CategorieType;
use ProductBundle\Form\ImageAccueilType;
use ProductBundle\Form\MarqueType;
use ProductBundle\Form\ProduitType;
use ProductBundle\Form\PromotionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $marque = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $image =$this->getDoctrine()->getRepository(ImageAccueil::class)->findOneBy(array('enabled'=>true));
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@Product\Default\index.html.twig',array("produit"=>$produit,"marque"=>$marque,"image"=>$image->getImage()));
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
            $file=$marque->getLogo();

            if($file != null)
            {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $path="../web/logos";
                $file->move($path ,$fileName);
                $marque->setLogo("/logos/".$fileName);
            }
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
                $path="../web/images";
                $file->move($path ,$fileName);
                $produit->setImage("/images/".$fileName);
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
    public function UpdateProduitAction(Request $request,$id){
        $em = $this->getDoctrine()->getManager();
        $produit  = $this->getDoctrine()
            ->getRepository('ProductBundle:Produit')
            ->find($id);
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
            $em->persist($produit);
            $em->flush();
        }
        return $this->render('@Product\Default\UpdateProduit.html.twig',array("form"=>$form->createView()));
    }
    public function AfficherProduitAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();

        //$form = $em->getRepository('ProfilBundle\formation')->findAll();
        $produit= $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $marque =$this->getDoctrine()
            ->getRepository(Marque::class)
            ->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        if($produit != null){

            return $this->render('@Product\Default\AfficherProduit.html.twig', array('produit' => $produit,
                'marque' => $marque, 'categorie' => $categorie));

        }
        else {
            return new Response("hhhh");        }
    }
    public function DeleteProduitAction($id)
    {
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
        $em= $this->getDoctrine()->getManager();

        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('Afficher_Produit');
    }
    public function SetImageAccueilAction(Request $request){
        $image = new ImageAccueil();
        $form = $this->createForm(ImageAccueilType::class,$image);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $status = $request->get('Status');
            $file=$image->getImage();
            $em = $this->getDoctrine()->getManager();
            $im = $this->getDoctrine()->getRepository(ImageAccueil::class)->findOneBy(array("enabled"=>true));
            if($file != null)
            {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $path="../web/images";
                $file->move($path ,$fileName);
                $image->setImage("/images/".$fileName);
            }
            if($status == "1"){
                $im->setEnabled(false);
                $image->setEnabled(true);
            }
            else{
                $image->setEnabled(false);
            }
            if($im){
                $em->persist($im);
            }
            $em->persist($image);
            $em->flush();
            return $this->redirectToRoute("AfficherImagesAccueil");
        }
        return $this->render('@Product\Default\ImageAccueil.html.twig',array("form"=>$form->createView()));
    }
    public function CartAction(){
        $image =$this->getDoctrine()->getRepository(ImageAccueil::class)->findOneBy(array('enabled'=>true));
        return $this->render('@Product\Default\Cart.html.twig',array("image"=>$image->getImage()));
    }
}