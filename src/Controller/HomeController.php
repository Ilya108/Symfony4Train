<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 21/11/2018
 * Time: 15:41
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use App\Repository\ArticleRepository;
use Twig\Environment;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    protected $objLogger;

    /**
     * HomeController constructor.
     * @param LoggerInterface $objLogger
     */
    public function __construct( LoggerInterface $objLogger)
    {
        $this->objLogger = $objLogger;
    }

    /**
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function index(Request $request, TranslatorInterface $translator, ArticleRepository $articleRepository) :Response
    {
        $this->objLogger->debug('HomeController:index - debut');

        $articles = $articleRepository->findAll();

        $locale = $request->getLocale();

        $this->objLogger->debug('HomeController:index - fin');

        return $this->render('pages/home.html.twig' , [
            'locale' => $locale,
            'articles' => $articles
        ]);
    }
}