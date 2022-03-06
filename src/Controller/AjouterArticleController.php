<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\AjouterArticleType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AjouterArticleController extends AbstractController
{
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/ajouter_article", name="app_ajouter_article")
     */
    public function index(Request $request,FileUploader $fileUploader): Response
    {
        $article= new Article();
        $form = $this->createForm(AjouterArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            // Traitement des données recu du formilaire 
            $images = $form->get('image')->getData();
            if ($images) {
            $imageFileName = $fileUploader->upload($images);
            $article->setImage($imageFileName);
        }
                $this->manager->persist($article);
                $this->manager->flush();
                return $this->redirectToRoute('app_home');                
        }

        return $this->render('ajouter_article/index.html.twig', [
            'controller_name' => 'AjouterArticleController',
            'form' => $form->createView(),
        ]);
    }
}
