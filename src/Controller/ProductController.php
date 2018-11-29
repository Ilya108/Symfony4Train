<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\PaginatorService;
use Psr\Log\LoggerInterface;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 *
 * @Route("/product")
 */
class ProductController extends Controller
{
    private $objProductService;
    protected $objLogger;

    /**
     * ProductController constructor.
     * @param ProductService $objProductService
     * @param LoggerInterface $objLogger
     */
    public function __construct(ProductService $objProductService, LoggerInterface $objLogger)
    {
        $this->objProductService = $objProductService;
        $this->objLogger = $objLogger;
    }

    /**
     * @Route("/", name="product_index", methods="GET")
     *
     * @param Request $request
     * @param PaginatorService $paginatorService
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function index(Request $request, PaginatorService $paginatorService, ProductRepository $productRepository ): Response
    {
        $this->objLogger->debug('ProductController:index - debut');
        $products = $this->objProductService->getProducts($request, $productRepository);

        $paginatedPersons = $paginatorService->paginate($products, 1, 10);

        $this->objLogger->debug('ProductController:index - fin');
        return $this->render('product/index.html.twig', ['products' => $paginatedPersons]);
    }

    /**
     * @Route("/new", name="product_new", methods="GET|POST")
     *
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->objLogger->debug('ProductController:new - debut');

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $this->objProductService->createProduct($product);
            $this->addFlash(
                'notice',
                'Votre produit a été crée'
            );


        $this->objLogger->debug('ProductController:new - fin');
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     *
     * @param Product $product
     * @return Response
     */
    public function show(Product $product): Response
    {
        $this->objLogger->debug('ProductController:edit - debut');

        $this->objLogger->debug('ProductController:edit - fin');
        return $this->render('product/show.html.twig', ['product' => $product]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|POST")
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function edit(Request $request, Product $product): Response
    {
        $this->objLogger->debug('ProductController:edit - debut');
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // $this->getDoctrine()->getManager()->flush();
            $this->objProductService->editProduct($product);
            $this->addFlash(
                'notice',
                'Votre produit a été édité'
            );

        $this->objLogger->debug('ProductController:edit - fin');
            return $this->redirectToRoute('product_edit', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     *
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function delete(Request $request, Product $product): Response
    {
        $this->objLogger->debug('ProductController:delete - debut');
        //if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {


            $this->objProductService->deleteProduct($product);
            $this->addFlash(
                'notice',
                'Votre produit a été supprimé'
            );

        //}
        $this->objLogger->debug('ProductController:delete - fin');
        return $this->redirectToRoute('product_index');
    }
}
