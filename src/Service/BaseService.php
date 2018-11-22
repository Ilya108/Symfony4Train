<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 30/10/2018
 * Time: 13:54
 */

namespace App\Service;

use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of BaseService
 * BaseService est la classe implémentée par les services.
 *
 * @author geoffroyb
 */

class BaseService
{
    /**
     * @var LoggerInterface
     */
    protected $objLogger;

    /**
     * @var EntityManager
     */
    protected $objEm;

    /**
     * Initialisation du service.
     *
     * @param LoggerInterface        $objLogger
     * @param EntityManager $objOm
     */
    public function __construct(LoggerInterface $objLogger, EntityManagerInterface $objEm)
    {
        $this->objLogger = $objLogger;
        $this->objEm = $objEm;
    }
}