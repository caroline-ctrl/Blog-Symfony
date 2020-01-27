<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{



    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }



    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $manager)
    {

        $news = new Article();
        $formCreate = $this->createFormBuilder($news)
            ->add('title')
            ->add('content')
            ->add('image')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title'
            ])

            ->getForm();

        $formCreate->handleRequest($request);

        if ($formCreate->isSubmitted() && $formCreate->isValid()) {
            $news->setCreatedAt(new \DateTime());
            $manager->persist($news);
            $manager->flush();
            return $this->redirectToRoute('show', ["id" => $news->getId()]);
        }

        return $this->render('article/new.html.twig', [
            'formCreate' => $formCreate->createView()
        ]);
    }


    /**
     * @Route("/category", name="category")

     */
    public function category()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('article/category.html.twig', compact('categories'));
    }



    /**
     * @Route("/categoryGenre/{id}", name="categoryGenre")

     */
    public function categoryGenre($id)
    {
        $genres = $this->getDoctrine()->getRepository(Category::class)->find($id);

        return $this->render('article/categoryGenre.html.twig', compact('genres'));
    }





    /**
     * @Route("/fantastique", name="fantastique")

     */
    public function cat1()
    {
        $arts = $this->getDoctrine()->getRepository(Article::class)->findAll();

        return $this->render('article/fantastique.html.twig', compact('arts'));
    }




    /**
     * @Route("/scienceFiction", name="category/scienceFiction")

     */
    public function cat2()
    {

        $cats = $this->getDoctrine()->getRepository(Category::class)->findAll();


        return $this->render('article/scienceFiction.html.twig', [
            'cats' => $cats
        ]);
    }




    /**
     * @Route("/policier", name="category/policier")

     */
    public function cat3()
    {

        $cats = $this->getDoctrine()->getRepository(Category::class)->findAll();


        return $this->render('article/policier.html.twig', [
            'cats' => $cats
        ]);
    }






    /**
     * @Route("/article/{id}", name="show")
     */
    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }



    /**
     * @Route("/article/{id}/comment/", name="comment")
     */
    public function comment(Article $articleId, Request $request, EntityManagerInterface $manager)
    {
        $articleRepo = $this->getDoctrine()->getRepository(Article::class);
        $contenuArticle = $articleRepo->find($articleId);

        $com = new Comment();
        $formCom = $this->createFormBuilder($com)
            ->add('author')
            ->add('content')

            ->getForm();

        $formCom->handleRequest($request);

        if ($formCom->isSubmitted() && $formCom->isValid()) {
            $com->setCreatedAt(new \DateTime());
            $com->setArticle($contenuArticle);
            $manager->persist($com);
            $manager->flush();
            return $this->redirectToRoute('show', ["id" => $contenuArticle->getId()]);
        }

        return $this->render('article/comment.html.twig', [
            'formCom' => $formCom->createView()
        ]);
    }




    /**
     * @Route("/article/{id}/modif", name="modif")
     */
    public function modif(Article $article, Request $request, EntityManagerInterface $manager)
    {

        $formModif = $this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title'
            ])
            ->getForm();

        $formModif->handleRequest($request);
        if ($formModif->isSubmitted() && $formModif->isValid()) {
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('show', ["id" => $article->getId()]);
        }

        return $this->render('article/modif.html.twig', [
            'formModif' => $formModif->createView()
        ]);
    }



    /**
     * @Route("/article/{id}/supp", name="supp")
     */
    public function supp(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('index');
    }
}
