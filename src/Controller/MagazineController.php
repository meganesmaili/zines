<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Magazine;
use App\Form\CategorieFormType;
use App\Form\MagazineFormType;
use App\Repository\CategorieRepository;
use App\Repository\MagazineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagazineController extends AbstractController
{
    #[Route('/', name: 'app_magazine')]
    public function index(MagazineRepository $magazineRepository): Response
    {
        return $this->render('magazine/index.html.twig', [
            'magazines' => $magazineRepository->findAll(),
            'details'=>$magazineRepository->find(3)
        ]);
    }

    #[Route('/magazine/{id}', name: 'app_id', requirements:["id"=>"\d+"])]
    public function details(int $id, MagazineRepository $magazineRepository): Response
    {
        return $this->render('magazine/details.html.twig', [
            'magazine' => $magazineRepository->find($id)
        ]);
    }
    #[Route('/categorie', name:'app_categorie')]
    public function allCategorie(CategorieRepository $categorieRepository) :Response
    {
        return $this->render('magazine/allCategorie.html.twig', [
            'categories'=> $categorieRepository->findAll()
        ]);
    }
    #[Route('/categorie/new', name:'app_categorie_new')]
    public function newCategorie(Request $request, CategorieRepository $categorieRepository) :Response
    {
        $categorie = new Categorie();

        $form = $this->createForm(CategorieFormType::class, $categorie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie, true);

            $this->addFlash('success', 'Votre categorie a bien été ajouté !');

            $categorie = new Categorie();
            $form = $this->createForm(CategorieFormType::class, $categorie);
        }

        return $this->render('magazine/newCategorie.html.twig', [
            'form'=> $form->createView()
        ]);
    }
    /**
     * @Route("/product/edit/{id}")
     */
    #[Route('/categorie/edit/{id}', name:'app_categorie_edit', requirements:["id"=>"\d+"])]
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


    #[Route('/categorie/delet/{id}', name:'app_categorie_delet', requirements:["id"=>"\d+"], methods:['POST'])]
    public function remove( Categorie $categorie, CategorieRepository $categorieRepository, Request $request) :RedirectResponse
    
    {
      
        $tokenCsrf = $request->request->get('token');

        if ($this->isCsrfTokenValid('delet-categorie-'. $categorie->getId(), $tokenCsrf)) {
           
        $categorieRepository->remove($categorie, true);
        $this->addFlash('success', 'Votre categorie a bien été supprimée !');


        }

        
        return $this->redirectToRoute('app_categorie'); 

 
    }


    #[Route('/magazine/new',name:'app_magazine_new')]
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

            $magazine = new Magazine();
            $form = $this->createForm(MagazineFormType::class, $magazine);

            return $this->redirectToRoute('app_magazine');
            
        }

        return $this->render('magazine/newMagazine.html.twig',[
            'form' => $form->createView()
        ]);
    }
    #[Route('/magazine/edit/{id}', name:'app_magazine_edit', requirements:["id"=>"\d+"])]
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
    #[Route('/magazine/delet/{id}', name:'app_magazine_delet', requirements:["id"=>"\d+"], methods:['POST'])]
    public function removeMagazine( Magazine $magazine, MagazineRepository $magazineRepository, Request $request) :RedirectResponse
    
    {
      
        $tokenCsrf = $request->request->get('token');

        if ($this->isCsrfTokenValid('delet-magazine-'. $magazine->getId(), $tokenCsrf)) {
           
        $magazineRepository->remove($magazine, true);
        $this->addFlash('success', 'Votre magazine a bien été supprimée !');


        }

        
        return $this->redirectToRoute('app_magazine'); 

 
    }
   
}
