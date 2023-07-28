<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_post_index', methods: ['GET'])]
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $this->getParameter('max_post_per_page');
        $offset = ($page - 1) * $limit;
        $paginator = $postRepository->getPostPaginator($offset, $limit);
        $max_page = ceil($paginator->count() / $limit);

        return $this->render('post/index.html.twig', [
            'paginator' => $paginator,
            'page' => $page,
            'max_page' => $max_page
//            'posts' => $postRepository->findBy(['status' => 'published'], ['id' => 'DESC']),
        ]);
    }

//    #[Route('post/{id1}', name: 'app_post_show', methods: ['GET'])]
//    #[ParamConverter('post', options: ['id' => 'id1'])] // 参数转换器，将 id1 转换为 Post 类中的 id
    #[Route('post/{id}', name: 'app_post_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Post $post, EntityManagerInterface $entityManager, CommentRepository $commentRepository, PaginatorInterface $paginator, TranslatorInterface $translator): Response
    {
        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            if ($commentForm->get('submit')->isClicked()) {
                /** @var Comment $comment */
                $comment = $commentForm->getData();
                $comment->setPost($post);
                $entityManager->persist($comment);
                $entityManager->flush();
            }

            $this->addFlash('success', $translator->trans('Your message is submitted.'));
        }

        $query = $commentRepository->getPaginatorQuery($post);
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'pagination' => $pagination,
            'comment_form' => $commentForm->createView(),
        ]);

    }
}
