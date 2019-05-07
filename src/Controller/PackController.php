<?php

namespace App\Controller;


use App\Entity\Pack;
use App\Entity\User;
use App\Entity\Service;
use App\Manager\PackManager;
use App\Entity\ServiceDuUser;
use App\Manager\ServiceDuUserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PackController extends AbstractController
{
    /**
     * @Route("/listePack", name="listePack")
     */
    public function ListePack()
    {
        $lesPacks=$this->getDoctrine()->getRepository(Pack::class)->findAll();
       
     
            return $this->render('packEtService/listePack.html.twig',array(
                'lesPacks'=>$lesPacks,
            ));
    }

     /**
     * @Route("/listeService/{idPack}", name="listeServiceAvecPack")
     */
    public function ListeService($idPack)
    {
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findByPack($idPack);
        foreach ($lesServices as $leService) {
            $packId=$leService->getPack()->getId();
        }

        return $this->render('packEtService/listeServiceAvecPack.html.twig',array(
            'lesServices'=>$lesServices,
            'packId'=>$packId     
        ));
    }

     /**
     * @Route("/choixDuPack/{idPack}", name="choixDuPack")
     */
    public function choixDuPack($idPack,PackManager $pm,ObjectManager $om,ServiceDuUserManager $sum)
    {
        // les services du packs
        $lesServices=$this->getDoctrine()->getRepository(Service::class)->findByPack($idPack);
        // avoir acces au données du pack(array)
        $pack=$this->getDoctrine()->getRepository(Pack::class)->findById($idPack);
        // objet pack
        $lePack=$pack[0];
            //parcour la collection
            foreach ($lesServices as $leService)
            {
                //les ids du pack des services
               $servicesIdDuPack=$leService->getPack()->getId();
            }
            // rendre le pack actif si il est selectionner
            $pm->ActifPack($lePack,$servicesIdDuPack,$om);
            //active les services du pack activé
            $pm->ServiceDuPackActif($lesServices,$lePack,$servicesIdDuPack,$om);
            // les services des users
        $lesServicesDesUsers=$this->getDoctrine()->getRepository(ServiceDuUser::class)->findAll();
        // le user actuel
        $user=$this->getUser();
         // ajoute les services d'un pack a des nouveaux services du user
        $pm->ServiceDuUserAvecPack($sum,$user,$lesServices,$lePack,$servicesIdDuPack,$om);
       
        
        return $this->redirectToRoute('vosServices');
       
    }


}
