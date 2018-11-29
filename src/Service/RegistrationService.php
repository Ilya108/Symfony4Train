<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 30/10/2018
 * Time: 13:49
 */

namespace App\Service;

use App\Entity\Users;
use App\Service\BaseService;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RegistrationService
 * @package App\Service
 */
class RegistrationService extends BaseService
{
    protected $objEm;
    protected $objLogger;

    /**
     * RegistrationService constructor.
     * @param LoggerInterface $objLogger
     * @param EntityManagerInterface $objEm
     */
    public function __construct(LoggerInterface $objLogger, EntityManagerInterface $objEm)
    {
        $this->objEm = $objEm;
        $this->objLogger = $objLogger;
    }

    /**
     * Création d'un nouveau utilisateur
     *
     * @param Users $objUser
     */
    public function registerUser(Users $objUser)
    {
        $this->objLogger->debug('RegistrationService:registerUser - debut');
        $this->objEm->persist($objUser);
        $this->objEm->flush();
        $this->objLogger->debug('RegistrationService:registerUser - fin');
    }
}