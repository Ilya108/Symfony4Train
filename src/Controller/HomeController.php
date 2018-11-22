<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 21/11/2018
 * Time: 15:41
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Environment;


class HomeController extends AbstractController
{
    public function index(Request $request, TranslatorInterface $translator) :Response
    {
        $locale = $request->getLocale();


        return $this->render('pages/home.html.twig' , [
            'locale' => $locale
        ]);
    }
}