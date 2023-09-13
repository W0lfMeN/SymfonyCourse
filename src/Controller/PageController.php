<?php

namespace App\Controller;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface; //Controla el tema de la base de datos

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; //Controlador principal de symfony

use App\Form\CommentType; //Formulario

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; //Maneja el sistema de rutas

class PageController extends AbstractController {

    #[Route('/', name: 'home')]
    public function Home(Request $request, EntityManagerInterface $entityManager): Response {
        
        $form = $this->createForm(CommentType::class); //Crea el formulario
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($form->getData()); //Prepara la consulta sql para guardar los datos
            $entityManager->flush(); //Guarda los datos

            return $this->redirectToRoute('home'); //Redirecciona al usuario a la ruta Home
        }


        $search = $request->get('search');
        //return new Response("Hola pagina de inicio " . $search);
        return $this->render('home/home.html.twig', [
            'comments' => $entityManager->getRepository(Comment::class)->findAll(),
            'search' => $search,
            'form' => $form->createView()
        ]);
    }
}