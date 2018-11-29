<?php

namespace App\Controller;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{


    protected $objLogger;

    /**
     * ArticleController constructor.
     * @param LoggerInterface $objLogger
     */
    public function __construct(LoggerInterface $objLogger)
    {

        $this->objLogger = $objLogger;
    }

    /**
     * @Route("/article", name="article_index")
     *
     * @param ArticleRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArticleRepository $repository)
    {
        $this->objLogger->debug('ArticleController:index - debut');
        $articles = $repository->findAll();

        $this->objLogger->debug('ArticleController:index - fin');
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/article/{id}", name="article_show")
     *
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function show(Article $article)
    {
        $this->objLogger->debug('ArticleController:show - debut');

        $this->objLogger->debug('ArticleController:show - fin');
        return $this->render('article/show.html.twig', [
            'article' => $article

        ]);
    }
}
