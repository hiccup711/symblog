<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findBy(['status' => 'published'], ['id' => 'DESC']),
        ]);
    }

//    #[Route('post/{id1}', name: 'app_post_show', methods: ['GET'])]
//    #[ParamConverter('post', options: ['id' => 'id1'])] // 参数转换器，将 id1 转换为 Post 类中的 id
    #[Route('post/{id}', name: 'app_post_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /** @var Comment $comment */
            $comment = $commentForm->getData();
            $comment->setPost($post);
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comment_form' => $commentForm->createView(),
        ]);

    }
}
