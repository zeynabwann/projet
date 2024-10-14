<?php

namespace App\Controller;
use App\Form\ClientType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ClientRepository;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients.index', methods:['GET'])]
    public function index(ClientRepository $clientRepository): Response
    {
        $client = $clientRepository->findAll();
        return $this->render('client/index.html.twig', [
            'datas' => $client

        ]);
    }


    #[Route('/clients/show/{id?}', name: 'clients.show', methods:['GET'])]
    public function show(?int $id = null): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/search/telephone', name: 'clients.searchClientByTelephone', methods:['GET'])]
    public function searchClientByTelephone(Request $request): Response
    { 
        $telephone = $request->query->get('tel');
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/remove/{id?}', name: 'clients.remove', methods:['GET'])]
    public function remove(?int $id = null): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }



    #[Route('/clients/store', name: 'clients.store', methods: ['GET', 'POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {  
        $client=new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $client->setCreateAt(new \DateTimeImmutable());
            $client->setupdateAt(new \DateTimeImmutable());
            $entityManager->persist($client);
            $entityManager->flush();

         return $this->redirectToRoute('clients.index'); 



        }
        return $this->render('client/form.html.twig', [
            'formClient' => $form->createView(),
        ]);
    }
}
