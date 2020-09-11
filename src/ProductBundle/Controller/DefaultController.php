<?php

namespace ProductBundle\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use ProductBundle\Entity\Categorie;
use ProductBundle\Entity\Commande;
use ProductBundle\Entity\ImageAccueil;
use ProductBundle\Entity\Livraison;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $topproduit =$this->getDoctrine()->getRepository(Produit::class)->getTopProducts();
        $marque = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $image =$this->getDoctrine()->getRepository(ImageAccueil::class)->findOneBy(array('enabled'=>true));
        $produit = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('@Product\Default\index.html.twig',array("topProduit"=>$topproduit,"produit"=>$produit,"categorie"=>$categorie,"marque"=>$marque,"image"=>$image->getImage()));
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
        $marque = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\AjouterCategorie.html.twig',array("form"=>$form->createView(),'marque'=>$marque,'categorie'=>$categories));
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
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\AjouterMarque.html.twig',array("marque"=>$marques,"categorie"=>$categorie,"form"=>$form->createView()));
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
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\AjouterProduit.html.twig',array("marque"=>$marques,"categorie"=>$categorie,"form"=>$form->createView()));
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
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\AjouterPromotion.html.twig',array("marque"=>$marques,"categorie"=>$categorie,"form"=>$form->createView()));
    }
    public function AfficherProduitsAction(){
        $produits = $this->getDoctrine()->getRepository(Produit::class)
            ->findAll();
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\AfficherProduit.html.twig', array('marque'=>$marques,'categorie'=>$categorie,'produits' => $produits));

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
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\UpdateProduit.html.twig',array('marque'=>$marques,'categorie'=>$categorie,"form"=>$form->createView()));
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
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\ImageAccueil.html.twig',array('marque'=>$marques,'categorie'=>$categorie,"form"=>$form->createView()));
    }
    public function CartAction(){
        $image =$this->getDoctrine()->getRepository(ImageAccueil::class)->findOneBy(array('enabled'=>true));
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\Cart.html.twig',array('marque'=>$marques,'categorie'=>$categorie,"image"=>$image->getImage()));
    }
    public function CommandeAction(Request $request)
    {
        $content = $request->getContent();
        $json = json_decode($content, true);
        $uni = md5(uniqid()) . '.' . '001';
        foreach ((array)$json as $j) {
            $produit = $this->getDoctrine()->getRepository(Produit::class)->find($j['id']);
            $commande = new Commande();
            $commande->setNumcommande($uni);
            $commande->setDateCommande(new \DateTime('now'));
            $commande->setMethodePaiment('espece');
            $commande->setQuantite($j['quantite']);
            $commande->setProduit($produit);
            $tot = $j['prix'] * $j['quantite'];
            $commande->setEnable(0);
            $commande->setEmailsend(0);
            $commande->setTotale($tot);
            $commande->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();
        }
        return new JsonResponse($uni);
    }
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function livraisonAction($uni){
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        if ($authChecker->isGranted('ROLE_SIMPLE_USER')) {
            $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy(['numCommande' => $uni]);
            $totales=0;
            foreach ($commandes as $c) {
                $totales=$totales+$c->getTotale();
            }
            if ($totales<40){
                $totales=$totales+7;
            }
            $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
            return $this->render('@Product\Default\Commande.html.twig',array('marque'=>$marques,'categorie'=>$categorie,"commandes"=>$commandes,"totales"=>$totales,"numCommande"=>$uni));
        }else{
            return $this->redirectToRoute("product_homepage");
        }
    }
    public function validationAction(Request $request){
        $content = $request->getContent();
        $json = json_decode($content, true);
        //return new jsonresponse($json[0]);
        $liv = new Livraison();
        $liv->setFirstname($json[0]) ;
        $liv->setLastname($json[1]) ;
        $liv->setStreetadress($json[2]) ;
        $liv->setPhone($json[3]) ;
        $liv->setEmail($json[4]) ;
        $liv->setNumCommande($json[5]);
        $em = $this->getDoctrine()->getManager();
        $liv->setUser($this->getUser());
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy(['numCommande' => $liv->getNumCommande()]);
        foreach ($commandes as $c){
            $c->setEnable(1);
        }
        $em->persist($liv);
        $em->flush();
        return new jsonresponse("Success");
    }
    public function successvalidationAction(){
        return $this->redirectToRoute('product_homepage');
    }
    public function checkPromotionAction(Request $request){
        $content = $request->getContent();
        $json = json_decode($content, true);
        $promos = [];
        foreach ($json as $j){
            $produit = $produit = $this->getDoctrine()->getRepository(Produit::class)->find($j['id']);
            $promotion = $this->getDoctrine()->getRepository(Promotion::class)->findBy(array("produit"=>$produit));
            if($promotion){
                $date = new \DateTime("now");
                foreach ($promotion as $p){
                    if($date > $p->getDateDebut() && $date < $p->getDateFin()){
                        array_push($promos,$p);
                    }
                    else{
                        return new JsonResponse("Empty");
                    }
                }
            }else{
                $categorie =  $produit->getCategorie();
                $marque = $produit->getMarque();
                $pr = $this->getDoctrine()->getRepository(Promotion::class)->findBy(array("categorie"=>$categorie));

                if($pr){
                    $date = new \DateTime("now");
                    foreach ($pr as $p){
                        if($date > $p->getDateDebut() && $date < $p->getDateFin()){
                            return new JsonResponse($p->getId());
                        }
                        else{
                            return new JsonResponse("Empty");
                        }
                    }
                }else{
                    $pro = $this->getDoctrine()->getRepository(Promotion::class)->findBy(array("marque"=>$marque));
                    if($pro){
                        $date = new \DateTime("now");
                        foreach ($pr as $p){
                            if($date > $p->getDateDebut() && $date < $p->getDateFin()){
                                return new JsonResponse($p->getId());
                            }
                            else{
                                return new JsonResponse("Empty");
                            }
                        }
                    }
                }
            }
        }
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers, $encoders);
        $formatted = $serializer->normalize(['promotion' => $promos]);
        return new JsonResponse($formatted);
    }
    public function commandesclAction(Request $request)
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy(array('user'=>$this->getUser()));
        $paginator =$this->get('knp_paginator');
        $result= $paginator->paginate(
            $commandes,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\mescommandes.html.twig',array('marque'=>$marques,'categorie'=>$categorie,"valable"=>$result));
    }
    public function commandesadAction(Request $request )
    {
        $repository = $this->getDoctrine()
            ->getRepository(Commande::class);
        $query = $repository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();
        $commandes = $query->getResult();
        foreach ($commandes as $c) {
            $daten = new \DateTime('now');
            $date =$c->getDateCommande();
            $format = 'Y-m-d';
            $d=$date->format($format);
            $datem1= date('Y-m-d', strtotime($d. ' + 3 days'));
            $datem2= date('Y-m-d', strtotime($d.' + 9 days'));
            $diff = date_diff($daten, $date);
            if ($c->getEnable()==0 && $diff->d >= 3 && $diff->d <= 6 ){
                $user=$c->getUser();
                $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')->setUsername('monguide07@gmail.com')->setPassword('Root123@');

                $mailer = \Swift_Mailer::newInstance($transport);
                $message = \Swift_Message::newInstance('Vérifier votre compte !')
                    ->setFrom(array('monguide07@gmail.com' => 'Service Water'))
                    ->setTo(array($user->getEmail() => $user->getNom()." ".$user->getPrenom()))
                    ->setBody("Bonjour ".$user->getNom()." , \n
            C'est presque fini ! \n
            Utilisez le lien de vérification ci-dessous pour terminer votre inscription. \n
            http://127.0.0.1:8000/verify/".$user->getUsername()."
            Merci.
            equipe team-42"
                        , 'text/plain');
                $result = $mailer->send($message);
            }
            if($diff->d > 6){
                $em = $this->getDoctrine()->getManager();
                $em->remove($c);
                $em->flush();

            }

        }
        $repository = $this->getDoctrine()
            ->getRepository(Commande::class);
        $query = $repository->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery();

        $commandes = $query->getResult();
        $paginator =$this->get('knp_paginator');
        $result= $paginator->paginate(
            $commandes,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\adcommandes.html.twig',array('marque'=>$marques,'categorie'=>$categorie,"commandes"=>$result));
    }
    public function FactureAction($idliv){
        $livraison=$this->getDoctrine()->getRepository(Livraison::class)->findOneBy(array('id'=>$idliv));
        $numcommande=$livraison->getNumCommande();
        $commande=$this->getDoctrine()->getRepository(Commande::class)->findby(array('numCommande'=>$numcommande));
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\Facture.html.twig', array(
            'livraison'  => $livraison,
            'commande'  => $commande,
            'marque'=>$marques,'categorie'=>$categorie
        ));
    }
    public function pdfFactureAction($idliv){
        $livraison=$this->getDoctrine()->getRepository(Livraison::class)->find($idliv);
        $numcommande=$livraison->getNumCommande();
        $commande=$this->getDoctrine()->getRepository(Commande::class)->findby(array('numCommande'=>$numcommande));
        $name = $fileName = md5(uniqid());
        $html = $this->renderView('@Product\Default\Facture.html.twig', array(
            'livraison'  => $livraison,
            'commande'  => $commande,
        ));
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            $name.'.pdf'
        );
    }
    public function livraisonAdminAction()
    {
        $livraison=$this->getDoctrine()->getRepository(Livraison::class)->findAll();
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\ListeLivraisonAdmin.html.twig', array(
            'marque'=>$marques,'categorie'=>$categorie,
            'livraison'  => $livraison,
        ));
    }
    public function livraisonClientAction()
    {
        $livraison=$this->getDoctrine()->getRepository(Livraison::class)->findby(array('user'=>$this->getuser()));
        $marques = $this->getDoctrine()->getRepository(Marque::class)->findAll();
        $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('@Product\Default\ListeLivraisonClient.html.twig', array(
            'marque'=>$marques,'categorie'=>$categorie,
            'livraison'  => $livraison,
        ));
    }
    public function afficherparCategorieAction($id,Request $request){
        $categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($id);

        $categories=$this->getDoctrine()->getRepository(Categorie::class)->findAll();

        $marque=$this->getDoctrine()->getRepository(Marque::class)->findAll();
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findby(array('categorie'=>$categorie));
        $paginator =$this->get('knp_paginator');
        $result= $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->render('@Product\Default\ProduitsparCategorie.html.twig',array("valable"=>$result,"categorie"=>$categories,"marque"=>$marque));

    }
    public function afficherparMarquesAction($id,Request $request){
        $marque=$this->getDoctrine()->getRepository(Marque::class)->find($id);

        $categories=$this->getDoctrine()->getRepository(Categorie::class)->findAll();

        $marques=$this->getDoctrine()->getRepository(Marque::class)->findAll();
        $produits=$this->getDoctrine()->getRepository(Produit::class)->findby(array('marque'=>$marque));
        $paginator =$this->get('knp_paginator');
        $result= $paginator->paginate(
            $produits,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
        return $this->render('@Product\Default\ProduitsparCategorie.html.twig',array("valable"=>$result,"categorie"=>$categories,"marque"=>$marques));

    }

}