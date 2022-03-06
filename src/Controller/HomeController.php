<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $repoCategory;
    private $repoArticle;
    private $userRepo;
    public function __construct(ArticleRepository $repoArticle,CategoryRepository $repoCategory,UserRepository $userRepo)
    {
        $this->repoArticle = $repoArticle;
        $this->repoCategory = $repoCategory;
        $this->userRepo = $userRepo;

    }
    


    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        $user=$this->getUser();
        if(isset($user)){
            $category = $this->repoCategory->findAll();
            $articles = $this->repoArticle->findAll();
            return $this->render("home/index.html.twig",[
                'articles' => $articles,
                'category' => $category,
                
            ]);
        }else{
            return $this->redirectToRoute('security_login');
        }
       
       

    }
    /**
     * @Route("/show/{id}", name="show")
     */
    public function show(Article $article): Response
    {

        
        if(!$article){
            return $this->redirectToRoute('app_home');
        };

        return $this->render("show/index.html.twig",[
            'articles' => $article,


        ]);
    }
    /**
     * @Route("/showArticlesByCategory/{id}", name="show_article")
     */
    public function showArticlesByCategory(?Category $category): Response
    {
        
        if($category){

            $articles = $category->getArticles()->getValues();

        }else{
           return $this->redirectToRoute('app_home');
        }
        $categories = $this->repoCategory->findAll();
      
        return $this->render("home/index.html.twig",[
            'articles' => $articles,
            'category' => $categories,
        ]);
    }

}
