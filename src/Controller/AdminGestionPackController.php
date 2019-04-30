<?php

namespace App\Controller;


use App\Entity\Pack;
use App\Form\CreatePackType;
use App\Manager\PackManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AdminGestionPackController extends AbstractController
{
    /**
     * @Route("/homeAdmin", name="homeAdmin")
     */
    public function homeAdmin()
    {
        return $this->render('Admin/accueilAdmin.html.twig');
    }

     /**
     * @Route("/gestion/ajouter/addPack", name="addPack")
     */
    public function addPackAndListPack(Request $request,PackManager $pm,ObjectManager $om)
    {

        $lesPacks=$this->getDoctrine()->getRepository(Pack::class)->findAll();

        $form= $this->createForm(CreatePackType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data=$form->getData();
            $pm->AddPack($data,$om);
            return $this->redirectToRoute('addPack');
        }
        return $this->render('Admin/gestionDesPacks.html.twig',[
            'form' =>$form->createView(),'lesPacks'=>$lesPacks]);
   
    }

     /**
     * @Route("/gestion/supprimer/deletePack/{id}", name="deletePack")
     */
    public function deletePack($id,PackManager $pm,ObjectManager $om)
    {
    
   
        $lePack=$this->getDoctrine()->getRepository(Pack::class)->findOneById($id);
        $pm->DeletePack($lePack,$om);    
        return $this->redirectToRoute('addPack');
     
        return $this->render('Admin/gestionDesPacks.html.twig');
   
    }

     /**
     * @Route("/gestion/changer/changePack/{id}", name="changePack")
     */
    public function changePack($id,Request $request,PackManager $pm,ObjectManager $om)
    {
        $lePack=$this->getDoctrine()->getRepository(Pack::class)->findOneById($id);  
        $form2= $this->createForm(CreatePackType::class);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid())
        {
            $data=$form2->getData();
            $pm->ChangePack($lePack,$om,$data);  
            return $this->redirectToRoute('addPack');
        }
     
        return $this->render('Admin/modifierUnPack.html.twig',[
            'form2' =>$form2->createView()]);
   
    }

  
}