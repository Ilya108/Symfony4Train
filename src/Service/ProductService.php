<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 23/11/2018
 * Time: 09:41
 */

namespace App\Service;

use App\Entity\Product;
use App\Service\BaseService;
use App\Repository\ProductRepository;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductService
 * @package App\Service
 */
class ProductService extends BaseService
{
    protected $objEm;
    protected $objLogger;

    /**
     * ProductService constructor.
     * @param LoggerInterface $objLogger
     * @param EntityManagerInterface $objEm
     */
    public function __construct(LoggerInterface $objLogger, EntityManagerInterface $objEm)
    {
        $this->objEm = $objEm;
        $this->objLogger = $objLogger;
    }

    /**
     * Création d'un produit
     *
     * @param Product $product
     */
    public function createProduct(Product $product)
    {
        $this->objLogger->debug('ProductService:createProduct - debut');
        $this->objEm->persist($product);
        $this->objEm->flush();
        $this->objLogger->debug('ProductService:createProduct - fin');
    }

    /**
     * Edition d'un produit
     *
     * @param Product $product
     */
    public function editProduct(Product $product)
    {
        $this->objLogger->debug('ProductService:editProduct - debut');
        $this->objEm->persist($product);
        $this->objEm->flush();
        $this->objLogger->debug('ProductService:editProduct - fin');
    }

    /**
     * Suppression d'un produit
     *
     * @param Product $product
     */
    public function deleteProduct(Product $product)
    {
        $this->objLogger->debug('ProductService:deleteProduct - debut');
        $this->objEm->persist($product);
        $this->objEm->flush();
        $this->objLogger->debug('ProductService:deleteProduct - fin');
    }

    /**
     * Pépare la requête pour la pagination et le tri par nom
     *
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return \Doctrine\ORM\Query
     */
    public function getProducts(Request $request, ProductRepository $productRepository)
    {
        $this->objLogger->debug('ProductService:getProducts - debut');
        $this->objLogger->debug('ProductService:getProducts - Récupération des produits');


        $queryBuilder = $productRepository->createQueryBuilder('p');
        if ($request->query->getAlnum('filter')) {
            $queryBuilder
                ->where('p.name LIKE :name')
                ->setParameter('name', '%' . $request->query->getAlnum('filter') . '%');
        }
        $queryBuilder->orderBy('p.id', 'DESC');

        $productsQuery = $queryBuilder->getQuery();

        $this->objLogger->debug('ProductService:getProducts - fin');

        return $productsQuery;
    }

    /**
     * Permet de recuperer le dernier produit crée
     *
     * @return object
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getLastProduct()
    {
        $this->objLogger->debug('ProductService:getLastProduct - debut');
        $this->objLogger->debug('ProductService:getLastProduct - fin');


        return $this->objEm->getRepository(Product::class)->findLastProduct();
    }

}