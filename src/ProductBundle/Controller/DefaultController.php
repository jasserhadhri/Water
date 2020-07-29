<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Categorie;
use ProductBundle\Entity\Marque;
use ProductBundle\Form\CategorieType;
use ProductBundle\Form\MarqueType;
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
}
