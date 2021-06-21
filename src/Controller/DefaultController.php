<?php

// src/Controller/DefaultController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Category;
use App\Repository\CategoryRepository;


Class DefaultController extends AbstractController

{

    /**

     * @Route("/", name="app_index")

     */

    public function index(): Response

    {
    
        return $this->render('default/index.html.twig', [
    
           'bienvenue' => 'Des milliers de wild séries en illimité pour toi et toute ta meute !',
    
        ]);
    
    }

    public function navbarTop(CategoryRepository $categoryRepository): Response

    {

        return $this->render('layout/navbartop.html.twig', [

            'categories' => $categoryRepository->findBy([], ['id' => 'DESC'])

    ]);

    }

}