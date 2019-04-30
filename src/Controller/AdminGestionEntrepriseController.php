<?php

namespace App\Controller;


use App\Entity\Entreprise;
use App\Form\CreateEntrepriseType;
use App\Manager\EntrepriseManager;
use App\Entity\ServiceDuneEntreprise;
use App\Form\LesServicesdesEntreprisesType;
use App\Manager\ServiceDuneEntrepriseManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminGestionEntrepriseController extends AbstractController
{
    /**
     * @Route("/gestion/ajouter/addCompany", name="addCompany")
     */
    public function addEntrepriseAndListEtreprise(Request $request,EntrepriseManager $em,ObjectManager $om)
    {
        $lesEntreprises=$this->getDoctrine()->getRepository(Entreprise::class)->findAll();
        $lesServicesdesEntreprises=$this->getDoctrine()->getRepository(ServiceDuneEntreprise::class)->findAll();
       
        $form= $this->createForm(CreateEntrepriseType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $data=$form->getData();
            $em->AddCompany($data,$om);
            return $this->redirectToRoute('addCompany');
        }

        return $this->render('admin/gestionDesEntreprises.html.twig'
        ,[
            'form' =>$form->createView(),'lesEntreprises'=>$lesEntreprises,            
            'lesServicesdesEntreprises'=>$lesServicesdesEntreprises
        ]
        );
    }

     /**
     * @Route("/gestion/supprimer/deleteCompany/{id}", name="deleteCompany")
     */
    public function deleteEntreprise($id,EntrepriseManager $em,ObjectManager $om)
    {
    
   
        $uneEntreprise=$this->getDoctrine()->getRepository(Entreprise::class)->findOneById($id);
        $em->DeleteCompany($uneEntreprise,$om);    
        return $this->redirectToRoute('addCompany');
     
        return $this->render('Admin/gestionDesEntreprises.html.twig');
   
    }

     /**
     * @Route("/gestion/changer/changeCompany/{id}", name="changeCompany")
     */
    public function changeEntreprise($id,Request $request,EntrepriseManager $em,ObjectManager $om)
    {
        $uneEntreprise=$this->getDoctrine()->getRepository(Entreprise::class)->findOneById($id);  
        $form2= $this->createForm(CreateEntrepriseType::class);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid())
        {
            $data=$form2->getData();
            $em->ChangeCompany($uneEntreprise,$om,$data);  
            return $this->redirectToRoute('addCompany');
        }
     
        return $this->render('Admin/modifierUneEntreprise.html.twig',[
            'form2' =>$form2->createView()]);
   
    }


    /**
     * @Route("/gestion/servicesdesEntreprises/addUnServicedEntreprise/{id}", name="addUnServicedEntrepriseAdmin")
     */
    public function addUnServicedEntreprise($id,Request $request,ServiceDuneEntrepriseManager $sdem,ObjectManager $om)
    {
        $entreprise=$this->getDoctrine()->getRepository(Entreprise::class)->findById($id);
     
        $formLesServicesdesEntreprises=$this->createForm(LesServicesdesEntreprisesType::class);
        $formLesServicesdesEntreprises->handleRequest($request);

        if ($formLesServicesdesEntreprises->isSubmitted() && $formLesServicesdesEntreprises->isValid())
        {
            $uneEntreprise=$entreprise[0];
            $data=$formLesServicesdesEntreprises->getData();
            $sdem->AddServiceCompany($uneEntreprise,$data,$om);
            return $this->redirectToRoute('addCompany');
        }
     
        return $this->render('Admin/ajouterUnServicedEntreprise.html.twig',[
            'formLesServicesdesEntreprises' =>$formLesServicesdesEntreprises->createView()
        ]);
    }
   


    /**
     * @Route("/gestion/servicesdesEntreprises/modifyUnServicedEntreprise/{id}/{idServiceDuneEntreprise}", name="modifyUnServicedEntrepriseAdmin")
     */
    public function modifyUnServicedEntreprise($id,$idServiceDuneEntreprise,Request $request,ServiceDuneEntrepriseManager $sdem,ObjectManager $om)
    {
        $entreprise=$this->getDoctrine()->getRepository(Entreprise::class)->findById($id);
        $serviceDuneEntreprise=$this->getDoctrine()->getRepository(ServiceDuneEntreprise::class)->findById($idServiceDuneEntreprise);
       
       
       

        $formLesServicesdesEntreprises=$this->createForm(LesServicesdesEntreprisesType::class);
        $formLesServicesdesEntreprises->handleRequest($request);

        if ($formLesServicesdesEntreprises->isSubmitted() && $formLesServicesdesEntreprises->isValid())
        {
            $uneEntreprise=$entreprise[0];
            $unServiceDuneEntreprise=$serviceDuneEntreprise[0];
            $data=$formLesServicesdesEntreprises->getData();
           
            $sdem->ModifyServiceCompany($unServiceDuneEntreprise,$uneEntreprise,$data,$om);
            return $this->redirectToRoute('addCompany');
        }
     
        return $this->render('Admin/modifierUnServicedEntreprise.html.twig',[
            'formLesServicesdesEntreprises' =>$formLesServicesdesEntreprises->createView()
        ]);
   
    }

     /**
     * @Route("/gestion/servicesdesEntreprises/deleteUnServicedEntreprise/{id}", name="deleteUnServicedEntrepriseAdmin")
     */
    public function deleteUnServicedEntreprise($id,ServiceDuneEntrepriseManager $sdem,ObjectManager $om)
    {
        $serviceDuneEntreprise=$this->getDoctrine()->getRepository(ServiceDuneEntreprise::class)->findById($id);
        $unServiceDuneEntreprise=$serviceDuneEntreprise[0];
        $sdem->DeleteServiceCompany($unServiceDuneEntreprise,$om);
        return $this->redirectToRoute('addCompany');
    }

}
