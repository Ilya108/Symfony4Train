<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationType;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    private $objRegistrationService;
    protected $objLogger;

    /**
     * SecurityController constructor.
     * @param RegistrationService $objRegistrationService
     * @param LoggerInterface $objLogger
     */
    public function __construct(RegistrationService $objRegistrationService, LoggerInterface $objLogger)
    {
        $this->objRegistrationService = $objRegistrationService;
        $this->objLogger = $objLogger;
    }


    /**
     * @Route("/inscription", name="security_registration")
     *
     * @param Request $request
     * @param ObjectManager $manager
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->objLogger->debug('SecurityController:registration - debut');

        $user = new Users();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            /*$manager->persist($user);
            $manager->flush();*/
            $this->objRegistrationService->registerUser($user);
            $this->addFlash(
                'notice',
                'Votre compte a été crée'
            );
            $this->objLogger->debug('SecurityController:registration - fin');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * La méthode pour switcher la langue du site
     *
     * @Route("/local/{language}", name="security_switch_locale")
     *
     * @param Request $request
     * @param null $language
     * @param LoggerInterface $objLogger
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchLocale(Request $request, $language = null, LoggerInterface $objLogger)
    {
        $this->objLogger->debug('SecurityController:switchLocale - debut');

        if (null != $language) {
            $this->get('session')->set('_locale', $language);
        }

        $referer = $request->headers->get('referer');
        $this->objLogger->debug('SecurityController:switchLocale - fin');


        return $this->redirect($referer);
    }

    /**
     * @Route("/connexion", name="security_login")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(){
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     *
     */
    public function logout(){}
}
