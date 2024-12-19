<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Form\ClientSearchType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ClientController extends AbstractController
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    #[Route('/client/add', name: 'client_add')]
    public function addClient(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);

        // Handle the form request
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Save the client to the database
            $entityManager->persist($client);
            $entityManager->flush();

            // Add a flash message for success
            $this->addFlash('success', 'Client ajouté avec succès.');

            // Redirect to the client list
            return $this->redirectToRoute('client_index');
        }

        // Render the form
        return $this->render('client/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/clients', name: 'client_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        // Create the search form
        $searchForm = $this->createForm(ClientSearchType::class);
        $searchForm->handleRequest($request);
        
        // Build the query for fetching clients
        $queryBuilder = $this->clientRepository->createQueryBuilder('c');
    
        // If the form is submitted and valid, filter by telephone
        if ($searchForm->isSubmitted()) {
            $searchData = $searchForm->getData();
            if (!empty($searchData['telephone'])) {
                $queryBuilder->where('c.telephone LIKE :search')
                             ->setParameter('search', '%' . $searchData['telephone'] . '%');
            } else {
                // Ajout d'un message flash si le champ est vide
                $this->addFlash('error', 'Veuillez saisir un numéro de téléphone.');
            }
        }
    
        // Paginate the results
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // Page number
            3 // Limit of items per page
        );
    
        // Return the view with the paginated list and the search form
        return $this->render('client/index.html.twig', [
            'clients' => $pagination,
            'searchForm' => $searchForm->createView(),
        ]);
    }

    #[Route('/client/delete/{id}', name: 'client_delete')]
    public function deleteClient(int $id, EntityManagerInterface $entityManager): Response
    {
        $client = $this->clientRepository->find($id); // Utiliser la méthode find directement

        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }

        // Logique de suppression du client
        $entityManager->remove($client);
        $entityManager->flush();

        // Redirection ou réponse après la suppression
        return $this->redirectToRoute('client_index'); // Assurez-vous que 'client_index' est le bon nom de route
    }

    #[Route('/client/{id}/dettes', name: 'client_dettes')]
    public function viewDettes(int $id): Response
    {
        // Récupérer le client par son ID
        $client = $this->clientRepository->find($id);
    
        if (!$client) {
            throw $this->createNotFoundException('Client non trouvé');
        }
    
        // Récupérer les dettes associées au client
        $dettes = $client->getDettes();
    
        return $this->render('client/dettes.html.twig', [
            'client' => $client,
            'dettes' => $dettes,
        ]);
        
        
    }
    

    #[Route('/client/search', name: 'client_search')]
    public function search(Request $request, PaginatorInterface $paginator): Response
    {
        // Retrieve the search term from the request
        $searchTerm = $request->query->get('search');

        // Build the query to filter by telephone
        $queryBuilder = $this->clientRepository->createQueryBuilder('c')
            ->where('c.telephone LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%');

        // Paginate the results
        $pagination = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1), // Page number
            3 // Limit of items per page
        );

        // Return the view with the search results
        return $this->render('client/index.html.twig', [
            'clients' => $pagination,
        ]);
    }
}