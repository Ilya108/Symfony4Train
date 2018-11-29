<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostLike;
use App\Repository\PostLikeRepository;
use App\Repository\PostRepository;
use Psr\Log\LoggerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PostController
 * @package App\Controller
 */
class PostController extends AbstractController
{
    protected $objLogger;

    /**
     * PostController constructor.
     * @param LoggerInterface $objLogger
     */
    public function __construct( LoggerInterface $objLogger)
    {
        $this->objLogger = $objLogger;
    }

    /**
     * @Route("/posts", name="post_index")
     *
     * @param PostRepository $repo
     * @return Response
     */
    public function index(PostRepository $repo)
    {
        $this->objLogger->debug('PostController:index - debut');
        $this->objLogger->debug('PostController:index - fin');


        return $this->render('post/index.html.twig', [
            'posts' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/post/{id}", name="post_show")
     * @param Post $post
     * @return Response
     */
    public function show(Post $post)
    {
        $this->objLogger->debug('PostController:show - debut');

        $this->objLogger->debug('PostController:show - fin');
        return $this->render('post/show.html.twig', [
            'post' => $post

        ]);
    }

    /**
     * Permet de liker ou unliker un article
     *
     * @Route("/post/{id}/like", name="post_like")
     *
     * @param Post $post
     * @param ObjectManager $manager
     * @param PostLikeRepository $likeRepo
     * @return Response
     */
    public function like(Post $post, ObjectManager $manager,PostLikeRepository $likeRepo) : Response
    {
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => "Unauthorized"
        ], 403);

        if ($post->isLikedByUser($user)){
           $like = $likeRepo->findOneBy([
               'post' => $post,
               'user' => $user
           ]);

           $manager->remove($like);
           $manager->flush();

           return $this->json([
               'code' => 200,
               'message' => 'Like bien supprimé',
               'likes' => $likeRepo->count(['post' => $post])
           ], 200);
        }

        $like = new PostLike();
        $like->setPost($post)
            ->setUser($user);
        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like bien ajouté',
            'likes' => $likeRepo->count(['post' => $post])
        ], 200);

    }
}
