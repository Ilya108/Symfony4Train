<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Post;
use App\Entity\PostLike;
use App\Entity\Users;
use App\Entity\Product;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AppFixtures
 * @package App\DataFixtures
 */
class AppFixtures extends Fixture
{
    /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Création des fausses données dans la bdd
     *
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $users = [];
        // Création d'utilisateurs
        $user = new Users();
        $user->setUsername('John')
            ->setEmail('user@symfony.com')
            ->setPassword($this->encoder->encodePassword($user, 'password'));

        $manager->persist($user);
        $users[] = $user;

        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, 'password'));

            $manager->persist($user);
            $users[] = $user;

        }
        //Création des posts
        for ($i = 0; $i < 20; $i++) {
            $post = new Post();
            $post->setTitle($faker->sentence(6))
                ->setIntroduction($faker->paragraph())
                ->setContent('<p>' . join(',', $faker->paragraphs()) . '</p>');

            $manager->persist($post);
            //Création des "likes"
            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $like = new PostLike();
                $like->setPost($post)
                    ->setUser($faker->randomElement($users));

                $manager->persist($like);

            }
        }

        //Création des produits
        for ($i = 0; $i < 300; $i++) {
            $product = new Product();
            $product->setName($faker->word . $i);
            $product->setPrice(mt_rand(10, 100));
            $product->setDescription( $faker->paragraph(2));
            $manager->persist($product);
        }
        //Création des articles
        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence(6))
                ->setContent('<p>' . join(',', $faker->paragraphs()) . '</p>')
                ->setImage("http://placehold.it/350x150")
                ->setCreatedAt(new\DateTime());


            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            AppFixtures::class,
        );
    }
}
