<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{



    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();

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

        dump($news);
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






    /**
     * @Route("/article/{id}", name="show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }
}
