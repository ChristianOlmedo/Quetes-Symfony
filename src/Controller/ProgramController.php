<?php

// src/Controller/ProgramController.php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Program;
use App\Entity\Category;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
    /**

    * @Route("/programs", name="program_")

    */
class ProgramController extends AbstractController

{

    /**
    * Show all rows from Program's entity
    *
    * @Route("/", name="index")
    * @return Response A response instance
    */

    public function index(): Response

    {
        $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();
        return $this->render('program/index.html.twig', [

            'programs' => $programs,

        ]);
    }

    /**

     * The controller for the program add form

     * Display the form or deal with it

     *

     * @Route("/new", name="new")

     */

    public function new(Request $request) : Response

    {

        // Create a new Program Object

        $program = new Program();

        // Create the associated Form

        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request

        $form->handleRequest($request);

        // Was the form submitted ?

        if ($form->isSubmitted() && $form->isValid()) {

            // Deal with the submitted data
    
            // Get the Entity Manager
    
            $entityManager = $this->getDoctrine()->getManager();
    
            // Persist Program Object
    
            $entityManager->persist($program);
    
            // Flush the persisted object
    
            $entityManager->flush();
    
            // Finally redirect to categories list
    
            return $this->redirectToRoute('program_index');
    
        }

        // Render the form

        return $this->render('program/new.html.twig', ["form" => $form->createView()]);

    }

    /**
     * Getting a program by id
     * 
     * @Route("/show/{id<^[0-9]+$>}", name="show")
     * @return Response
     */

    public function show(Program $program): Response

    {
        /*$program = $this->getDoctrine()->getRepository(Program::class)->findOneBy(['id' => $id]);*/
        $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(['program' => $program]);
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id. ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
    
           'program' => $program,
           'seasons' => $seasons,
        ]);
    
    }

    /**
     * Getting a season by id
     * 
     * @Route("/{programId}/season/{seasonId}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @return Response
     */

     public function showSeason(Program $program, Season $season): Response

     {
        /*$program = $this->getDoctrine()->getRepository(Program::class)->find($programId);*/
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id. ' found in program\'s table.'
            );
        }

        /*$season = $this->getDoctrine()->getRepository(Season::class)->find($seasonId);*/
        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : '.$id. ' found in season\'s table.'
            );
        }

        $episodes = $this->getDoctrine()->getRepository(Episode::class)->findBy(['season' => $season->getId() ]);

        return $this->render('program/season_show.html.twig', [
    
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
         ]);
     }

         /**
     * @Route("/{programId}/seasons/{seasonId}/episodes/{episodeId}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     * @return Response
     */

    public function showEpisode(Program $program, Season $season, Episode $episode): Response
        
    {
        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program->getId() . ' found in program\'s table.'
            );
        }

        if (!$season) {
            throw $this->createNotFoundException(
                'No season with id : ' . $season->getId() . ' found in season\'s table.'
            );
        }

        if (!$episode) {
            throw $this->createNotFoundException(
                'No episode with id : ' . $episode->getId() . ' found in episode\'s table.'
            );
        }

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

}
