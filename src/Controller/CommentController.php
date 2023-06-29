<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[
        Route('/post/{post_id}/comment/{comment_id}/replay', name: 'replay_comment', options: ['expose' => true]),
        ParamConverter('post', options: ['id' => 'post_id']),
        ParamConverter('comment', options: ['id' => 'comment_id']),
    ]
    public function replyComment(Request $request, Post $post, Comment $parentComment, EntityManagerInterface $entityManager): Response
    {
        $replayComment = $this->createForm(CommentType::class, null, [
            'action' => $request->getUri()
        ]);

        $replayComment->handleRequest($request);

        if ($replayComment->isSubmitted() && $replayComment->isValid()) {
            /**
             * @var Comment $data
             */
            $data = $replayComment->getData();
            $data->setParent($parentComment);
            $data->setPost($post);

            $entityManager->persist($data);

            $entityManager->flush();

            return $this->redirectToRoute('app_post_show', [
                'id' => $post->getId()
            ]);
        }

        return $this->render('comment/_reply_comment_form_html.twig', [
            'replay_comment_form' => $replayComment->createView()
        ]);
    }
}
