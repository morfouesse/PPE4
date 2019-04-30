<?php

namespace App\Controller;


use App\Entity\Pack;
use App\Entity\User;
use App\Entity\Service;
use App\Manager\PackManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PackController extends AbstractController
{
    /**
     * @Route("/listePack/{password}", name="listePack")
     */
    public function ListePack()
    {
        $lesPacks=$this->getDoctrine()->getRepository(Pack::class)->findAll();
        $password= $this->getUser()->getPassword();
      
     
            return $this->render('packEtService/listePack.html.twig',array(
                'lesPacks'=>$lesPacks,
                'password'=>$password
            ));
            
        

       
    }

     /**
     * @Route("/choixDuPack/{password}/{id}", name="choixDuPack")
     */
    public function choixDuPack($id,PackManager $pm,ObjectManager $om)
    {
        //l'id du pack des services
        $lePack=$this->getDoctrine()->getRepository(Pack::class)->findById($id);
        $password= $this->getUser()->getPassword();
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findByPack($lePack);
        dump($lesServices);
            //parcour la collection
            foreach ($lesServices as $key => $leService)
            {
                //les ids des services
               $serviceId=$leService->getId();
            }
        $pm->ActifPack($lePack,$serviceId,$om);
        $idPackDesServices=$leService->getPack()->getId();
        dump($serviceId);
        die;
        return $this->redirectToRoute('panier',['password'=>$password]);
       
    }


}
