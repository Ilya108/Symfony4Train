<?php

namespace App\Tests;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    private $objProductService;

    /**
     * On recupere l'entity manager à partir du kernel directement
     * Ca nous évite de déclarer un client qui n'est pas utilisé pour les tests unitaires.
     */
    public function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->objProductService = self::$container->get('App\Service\ProductService');
    }

    /**
     * Initialise le client avec un utilisateur admin pour acceder aux pages de l'administration.
     */
    private function initialiserClient()
    {
        //on utilise un utilisateur qui a le rôle admin
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en';
        $client = static::createClient(
            array(), array(
            'PHP_AUTH_USER' => 'ilya@actimage.com',
            'PHP_AUTH_PW' => 'testtest',
            'debug' => true,
            )
        );
        $client->followRedirects(true);

        return $client;
    }

    /**
     * Teste l'affichage de la liste des topics.
     */
    public function testIndexTopic()
    {
        $client = $this->initialiserClient();

        $crawler = $client->request('GET', '/product/');
        //on vérifie que le titre est présent sur la page de résultat
        $this->assertGreaterThan(
            0, $crawler->filter('html:contains("Product index")')
                ->count()
        );
    }

    /**
     * Tester un formulaire d'ajout d'un nouveau topic.
     */
    public function testCreateNewProduct()
    {
        $client = $this->initialiserClient();

        $crawler = $client->request('GET', '/product/new');

        //test de l'URL de la page de création d'un topic
        $this->assertEquals(
            200, $client->getResponse()
                ->getStatusCode(), 'Unexpected HTTP status code for GET /product/new'
        );

        $buttonCrawlerNode = $crawler->selectButton('save_product');

        $form = $buttonCrawlerNode->form(array(
            'product[name]' => 'Ordinateur',
            'product[price]' => '999',
            'product[description]' => 'Super ordi',

        ));

        $client->submit($form);
        //on assert que le formulaire est valide
        $this->assertTrue($client->getResponse()->isSuccessful());
        //test de verification que le topic à bien été créé
        $this->assertGreaterThan(
            0, $crawler->filter('html:contains("Create new Product")')
            ->count()
        );
    }

    /**
     * Teste l'edition de l'information sur un topic.
     */
    public function testEditProduct()
    {
        $client = $this->initialiserClient();

        // on essaie le formulaire de modification

        $objProduct = $this->objProductService->getLastProduct();

        $crawler = $client->request('GET', '/product/'.$objProduct->getId().'/edit');

        //test de l'URL
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /product/{id}/edit');
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit Product")')->count());
    }

    /**
     * Teste l'affichage de l'information sur un topic.
     */
    public function testShowTopic()
    {
        $client = $this->initialiserClient();

        $objProduct = $this->objProductService->getLastProduct();

        $crawler = $client->request('GET', '/product/'.$objProduct->getId());

        //on assert que le résultat existe
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Product")')->count());

        //on récupère un utilisateur inexistant
        $client->request('GET', '/product/999999');

        //on assert que le résultat existe
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Tester la suppression d'un topic.
     */
    public function testDeleteTopic()
    {
        $client = $this->initialiserClient();
        //recuperation d'objet
        $objProduct = $this->objProductService->getLastProduct();
        //construction d'URL pour supprimer le topic
        $crawler = $client->request(
            'DELETE', '/product/'.$objProduct->getId(),
            [],
            [],
            ['PHP_AUTH_USER' => 'ilya@actimage.com', 'PHP_AUTH_PW' => 'testtest']
        );

        //on assert que l'opération s'est bien passée
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
