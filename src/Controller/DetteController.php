<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Dette;
use Doctrine\ORM\EntityManagerInterface;




class DetteController extends AbstractController
{
    #[Route('/dette', name: 'app_dette')]
    public function index(DetteRepository $DetteRepository): Response
    {
        $DetteRepository = $entityManager->getRepository(Dette::class);
        $dettes = $DetteRepository->findAll();
        return $this->render('dettes/index.html.twig', [
            'dettes' => $dettes,
        ]);
    }


    #[Route('/dettes/show/{id}', name: 'detttes.show', methods:['GET'])]
    public function show(): Response
    {
        return $this->render('dette/index.html.twig', [
            'controller_name' => 'DetteController',
        ]);
    }





    public function ajoutDette(Request $request, EntityManagerInterface $entityManager): Response
    {  
        $dette=new Dette();
        $form = $this->createForm(DetteType::class, $dette);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $entityManager->persist($dette);
            $entityManager->flush();

         return $this->redirectToRoute('dette.index'); 



        }
        return $this->render('dette/form.html.twig', [
            'formDette' => $form->createView(),
        ]);
    }

    
}
