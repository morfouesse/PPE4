<?php

namespace App\Controller;


use App\Repository\ServiceRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(ServiceRepository $sr)
    {
        $lesServices=$sr->lesServicesLesPlusUtilise();
    
   

        return $this->render('home/accueil.html.twig',[
            'lesServices'=>$lesServices
        ]);
    }
}
