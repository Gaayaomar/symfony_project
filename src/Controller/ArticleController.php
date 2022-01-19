<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/article')]
class ArticleController extends AbstractController

{


    #[Route('/', name: 'article')]
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity

            ];
        }
        $total_panier = 0;
        foreach ($panierWithData as $item) {

            $total_panier = $total_panier + $item['quantity'];
        }
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'items'=>$panierWithData,
            'total_panier'=>$total_panier
        ]);
    }
    #[Route('/all', name: 'articleall')]
    public function all(Request $request,PaginatorInterface $paginator,SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $allarticles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $articles  = $paginator->paginate($allarticles,  $request->query->getInt('page', 1),20);
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity

            ];
        }
        $total_panier = 0;
        foreach ($panierWithData as $item) {

            $total_panier = $total_panier + $item['quantity'];
        }

        return $this->render('article/lister.html.twig', [
            'articles' => $articles,
            'items'=>$panierWithData,
            'total_panier'=>$total_panier

        ]);
    }

    #[Route('/show/{id}', name: 'article_show')]
    public function show($id,SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity

            ];
        }
        $total_panier = 0;
        foreach ($panierWithData as $item) {

            $total_panier = $total_panier + $item['quantity'];
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'items'=>$panierWithData,
            'total_panier'=>$total_panier
        ]);
    }


    /**
     *
     * @Route("/new/{titre}/{nom}/{price}/{category}",name="new_article")
     */
    public function new($titre,$nom,$category,$price)
    {
        $article = new Article();
        $article->setTitle($titre);
        $article->setImage($nom);
        $article->setDescription($category);
        $article->setPrice($price);
        $article->setDatecreation(new \DateTime());
        $Manager = $this->getDoctrine()->getManager();
        $Manager->persist($article);
        $Manager->flush();


        return $this->redirectToRoute('articleall');
    }
    /**
     *
     * @Route("/edit/{id}/{titre}/{nom}/{category}/{price}", name="article_edit")
     */
    public function edit(Request $request,$titre,$nom,$category,$id,$price )
    {

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if ($article){
            $article->setTitle($titre);
            $article->setImage($nom);
            $article->setDescription($category);
            $article->setPrice($price);
            $article->setDatecreation(new \DateTime());

            $Manager = $this->getDoctrine()->getManager();
            $Manager->persist($article);
            $Manager->flush();

        }

        return $this->redirectToRoute('articleall');}

    /**
     *
     * @Route("/delete/{id}", name="article_del")
     */
    public function delete(Request $request, $id){
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $Manager = $this->getDoctrine()->getManager();
        $Manager->remove($article);
        $Manager->flush();



        return $this->redirectToRoute('articleall');
    }

}

