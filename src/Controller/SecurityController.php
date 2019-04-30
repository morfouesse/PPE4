<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function inscription(Request $request,ObjectManager $manager,
    UserPasswordEncoderInterface $encoder)//importee les classe(ex : ObjectManager) ctrl+alt+i
    {
        $user= new User(); //crée une instance de classe User

        $form= $this->createForm(RegistrationType::class, $user); //utilise mon formulaire RegistrationType par rapport à mon objet user

        $form->handleRequest($request); //analyse la requete request
        
     // si le formulaires est soumis et que tout c'est champs sont valide
        if($form->isSubmitted() && $form->isValid())
        {
            $hash=$encoder->encodePassword($user, $user->getPassword()); //pour encoder, on specifie le user car sa encode par rapport au user
            
            $user->setPassword($hash); //je modifie ton password et je le remplace par le hash
            $user->addRole("ROLE_USER");
            $manager->persist($user); //faire persister dans le temps le user
            $manager->flush(); //capte le et fait le maintenant


            return $this->redirectToRoute('security_login');
        }


        return $this->render('security/registration.html.twig',[
            'form' =>$form->createView()
        ]);
    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(Request $request,ObjectManager $manager)  
    {
      
       //faire en sorte de atterir sur home
        return $this->render('security/login.html.twig');

    }

     /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
      
    }


    /**
     * @Route("/admin")
     */
    public function admin()
    {
       return $this->redirectToRoute('homeAdmin');
    }


    
}

