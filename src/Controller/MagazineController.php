<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Magazine;
use App\Entity\User;
use App\Form\CategorieFormType;
use App\Form\MagazineFormType;
use App\Repository\CategorieRepository;
use App\Repository\MagazineRepository;
use App\Repository\StockRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class MagazineController extends AbstractController
{  
    //Page d'accueil 

    #[Route('/', name: 'app_magazine')]
    public function index(MagazineRepository $magazineRepository, PaginatorInterface $paginatoreInterface, Request $request): Response
    {   
        $magazines = $paginatoreInterface->paginate(
            $magazineRepository->findAll(), //Requête SQL/DQL
            $request->query->getInt('page', 1), //Numéritation des pages 
            $request->query->getInt('numbers', 5) //Nombre d'enregistrement par page
        );
        return $this->render('magazine/index.html.twig', [
            'magazines' => $magazines,
            'details'=>$magazineRepository->find(5),
            
        ]);
    }

    // Page tableau magazine dès connexion

    #[Route('/home', name: 'app_home_magazine')]
    #[IsGranted('ROLE_USER')]
    public function home(MagazineRepository $magazineRepository, PaginatorInterface $paginatoreInterface, Request $request): Response
    {   
        $magazines = $paginatoreInterface->paginate(
            $magazineRepository->findAll(), //Requête SQL/DQL
            $request->query->getInt('page', 1), //Numéritation des pages 
            $request->query->getInt('numbers', 5) //Nombre d'enregistrement par page
        );
        return $this->render('magazine/home.html.twig', [
            'magazines' => $magazines,
            'details'=>$magazineRepository->find(5),
            
        ]);
    }
   
    //Page détails de chaque magazine

    #[Route('/magazine/{id}', name: 'app_id', requirements:["id"=>"\d+"])]
    public function details(int $id, MagazineRepository $magazineRepository): Response
    {
        return $this->render('magazine/details.html.twig', [
            'magazine' => $magazineRepository->find($id)
        ]);
    }

    //Page de toutes les catégories

    #[Route('/categorie', name:'app_categorie')]
    #[IsGranted('ROLE_USER')]
    public function allCategorie(CategorieRepository $categorieRepository, PaginatorInterface $paginatoreInterface, Request $request) :Response
    {
        $categories = $paginatoreInterface->paginate(
            $categorieRepository->findAll(), //Requête SQL/DQL
            $request->query->getInt('page', 1), //Numéritation des pages 
            $request->query->getInt('numbers', 5) //Nombre d'enregistrement par page
        );

        return $this->render('magazine/allCategorie.html.twig', [
            'categories'=> $categories
        ]);
    }

  //Ajout nouvelle carégories

    #[Route('/categorie/new', name:'app_categorie_new')]
    #[IsGranted('ROLE_USER')]
    public function newCategorie(Request $request, CategorieRepository $categorieRepository) :Response
    {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieFormType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie, true);

            $this->addFlash('success', 'Votre categorie a bien été ajouté !');

            //$categorie = new Categorie();
            //$form = $this->createForm(CategorieFormType::class, $categorie);
        }

        return $this->render('magazine/newCategorie.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    //Edition d'une categorie
    /**
     * @Route("/product/edit/{id}")
     */
    #[Route('/categorie/edit/{id}', name:'app_categorie_edit', requirements:["id"=>"\d+"])]
    #[IsGranted('ROLE_USER')]
    public function edit(Categorie $categorie, CategorieRepository $categorieRepository,  Request $request, int $id)
    {

        $form = $this->createForm(CategorieFormType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie, true);

            $this->addFlash('success', 'Votre categorie a bien été modifié !');

           return $this->redirectToRoute('app_categorie');
        }

        return $this->render('magazine/edit.html.twig', [
            'form'=> $form->createView()
        ]);

    }

    //Suppression d'une categorie

    #[Route('/categorie/delet/{id}', name:'app_categorie_delet', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function remove(Categorie $categorie, Request $request, CategorieRepository $categorieRepository): RedirectResponse
    {
        $tokenCsrf = $request->request->get('token');
        if ($this->isCsrfTokenValid('delet-categorie-'. $categorie->getId(), $tokenCsrf)) {
            $categorieRepository->remove($categorie, true);
            $this->addFlash('success', 'La catégorie à bien été supprimée');
        }

        return $this->redirectToRoute('app_categorie');
    }

    //Ajout d'un nouveau magazine
    
    #[Route('/magazine/new',name:'app_magazine_new')]
    #[IsGranted('ROLE_USER')]
    public function newMagazine(Request $request, MagazineRepository $magazineRepository) :Response
    {
        $magazine = new Magazine();

        $form = $this->createForm(MagazineFormType::class, $magazine);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 

            $magazineRepository->add($magazine, true);

            $this->addFlash(
               'success',
               'Votre magazine a bien été ajouté !'
            );

            //$magazine = new Magazine();
            //$form = $this->createForm(MagazineFormType::class, $magazine);

            return $this->redirectToRoute('app_magazine');
            
        }

        return $this->render('magazine/newMagazine.html.twig',[
            'form' => $form->createView()
        ]);
    }

    //Edition d'un Magazine

    #[Route('/magazine/edit/{id}', name:'app_magazine_edit', requirements:["id"=>"\d+"])]
    #[IsGranted('ROLE_USER')]
    public function editMagazine(Magazine $magazine, MagazineRepository $magazineRepository,  Request $request, int $id)
    {

        $form = $this->createForm(MagazineFormType::class, $magazine);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $magazineRepository->add($magazine, true);

            $this->addFlash('success', 'Votre magazine a bien été modifié !');

           return $this->redirectToRoute('app_magazine');
        }

        return $this->render('magazine/editMagazine.html.twig', [
            'form'=> $form->createView()
        ]);

    }

    //Suppression d'un magazine
    #[Route('/magazine/delet/{id}', name:'app_magazine_delet', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Magazine $magazine, Request $request, MagazineRepository $magazineRepository): RedirectResponse
    {
		// Récupère le jeton CSRF généré dans le formulaire
        $tokenCsrf = $request->request->get('token');

		// Vérifie si le jeton est correct avant d'effectuer une suppression
        if ($this->isCsrfTokenValid('delet-magazine-'. $magazine->getId(), $tokenCsrf)) {

			// Supprimer en BDD les données en lui passant l'objet de l'entité.
			// Le second paramètre est à mettre à "true", sinon les données sont seulement persistées et non supprimées.
            $magazineRepository->remove($magazine, true);

			// Enregistre un message flash à afficher dans le fichier Twig de votre choix
            $this->addFlash('success', 'Le magazine à bien été supprimé');
        }

		// Redirige l'utilisateur vers une autre page selon le nom de la route
        return $this->redirectToRoute('app_magazine');
    }

    // Stocks
    
    #[Route('/stock', name: 'app_stock')]
    public function stocks(StockRepository $stockRepository, PaginatorInterface $paginatorInterface, Request $request): Response
    {

        // Création de la pagination de résultats
        $stocks = $paginatorInterface->paginate(
            $stockRepository->findAll(), // Requête SQL/DQL
            $request->query->getInt('page', 1), // Numérotation des pages
            $request->query->getInt('numbers', 6) // Nombre d'éléments à afficher par page 
        );

        return $this->render('magazine/stock.html.twig', [
            'stocks' => $stocks,
        ]);
    }
    
    
   
}
