<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;
    public function indexAction()
    {
        return $this->render('UserBundle:Default:index.html.twig');
    }
    public function LoginAction(Request $request)
    {
        /** @var $session Session */
        $session = $request->getSession();

        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->render('UserBundle:Default:Login.html.twig',array(
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ));

    }
    public function RegisterAction(Request $request){
        $user = new User();
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, $request->get('userpasswordFirst'));
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        $user->setConfirmationToken($token);
        $user->setPassword($password);
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setNom($request->get('name'));
        $user->setPrenom($request->get('prenom'));
        $user->addRole("ROLE_SIMPLE_USER");
        $user->setDateNaissance(new \DateTime($request->get('date')));
        $user->setTitre($request->get('type'));
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')->setUsername('monguide07@gmail.com')->setPassword('so what00112233');

        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance('Vérifier votre compte !')
            ->setFrom(array('monguide07@gmail.com' => 'Service Water'))
            ->setTo(array($user->getEmail() => $user->getNom()." ".$user->getPrenom()))
            ->setBody("Bonjour ".$user->getNom()." , \n
            C'est presque fini ! \n
            Utilisez le lien de vérification ci-dessous pour terminer votre inscription. \n
            http://127.0.0.1:8000/verify/".$user->getUsername()."/".$token."
            Merci.
            equipe team-42"


                , 'text/plain');
        $result = $mailer->send($message);


        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("Success");
    }
    public function verifyEmailAction($username,$token){
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array('username'=>$username,'confirmationToken'=>$token));
        if($user){
            $user->setEnabled(1);
            $user->setConfirmationToken("");
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("valid");
        }else{
            return $this->redirectToRoute("nonValide");
        }


    }

}
