<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 26/11/2018
 * Time: 09:34
 */

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PaginatorService
 * @package App\Service
 */
class PaginatorService
{
    private $em;
    private $paginator;
    protected $requestStack;

    /**
     * PaginatorService constructor.
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManagerInterface $em, PaginatorInterface $paginator, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
    }

    /**
     * La pagination des pages
     *
     * @param $query
     * @param $pageNumber
     * @param $pageLimit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function paginate($query, $pageNumber, $pageLimit)
    {
        $request = $this->requestStack->getCurrentRequest();

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', $pageNumber),
            $pageLimit
        );

        return $pagination;
    }

}