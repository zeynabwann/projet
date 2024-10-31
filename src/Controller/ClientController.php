<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Form\ClientType;
use App\enum\StatusDette;
use App\Dto\ClientSearchDto;
use App\Form\DetteFilterType;
use App\Form\SearchClientType;
use App\Repository\DetteRepository;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientController extends AbstractController
{
    #[Route('/clients', name: 'clients.index', methods: ['GET', 'POST'])]
    public function index(ClientRepository $clientRepository, Request $request): Response
    {
        $clientSearchDto = new ClientSearchDto();
        $formSearch = $this->createForm(SearchClientType::class, $clientSearchDto);
        $formSearch->handleRequest($request);
        $page = $request->query->getInt('page', 1);
        $count = 0;
        $maxPage = 0;
        $limit = 6;
        if ($formSearch->isSubmitted($request) && $formSearch->isValid()) {

            $clients = $clientRepository->findClientBy($clientSearchDto, $page, $limit);
        } else {
            $clients = $clientRepository->paginateClients($page, $limit);
        }
        $count = $clients->count();
        $maxPage = ceil($count / $limit);

        return $this->render('client/index.html.twig', [
            'datas' => $clients,
            'formSearch' => $formSearch->createView(),
            'page' => $page, 
            'maxPage' => $maxPage,
        ]);
    }

    #[Route('/clients/show/{idClient?}', name: 'clients.show', methods: ['GET'])]
    public function show(int $idClient, ClientRepository $clientRepository, Request $request, DetteRepository $detteRepository): Response
    {
        $limit = 1;
        $page = $request->query->getInt('page', 1);

        $formSearch = $this->createForm(DetteFilterType::class);
        $formSearch->handleRequest($request);
        $client = $clientRepository->find($idClient);
        $status = $request->get("status", StatusDette::Impaye->value);

        if ($request->query->has('dette_filter')) {
            $status = $request->query->all('dette_filter')['status'];
        }
        $dettes = $detteRepository->findDetteByClient($idClient, $status, $limit, $page);

        $count = $dettes->count();
        $maxPage = ceil($count / $limit);
        return $this->render('client/dette.html.twig', [
            'dettes' => $dettes,
            'client' => $client,
            'status' => $status,
            'formSearch' => $formSearch->createView(),
            'page' => $page,
            'maxPage' => $maxPage,
        ]);
    }

    #[Route('/clients/search/telephone', name: 'clients.searchClientByTelephone', methods: ['GET'])]
    public function searchlientByTelephone(Request $request): Response
    {
        $telephone = $request->query->get('tel');
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }

    #[Route('/clients/remove/{id?}', name: 'clients.remove', methods: ['GET'])]
    public function remove(int $id): Response
    {
        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
        ]);
    }


    #[Route('/clients/store', name: 'clients.store', methods: ['GET', 'POST'])]
    public function store(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {
        $client = new Client();
        $client->setUser(new User());
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $photo->$form['photo']->getData();

            if (!$form->get('addUser')->getData()) {
                $client->setUser(null);
                
            }else{
                $user = $client->getUser();
                $hashedPassword = $encoder->hashPassword($user , $user->getPassword());
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('clients.index');
        }
        return $this->render('client/form2.html.twig', [
            'formClient' => $form->createView(),
        ]);
    }
}