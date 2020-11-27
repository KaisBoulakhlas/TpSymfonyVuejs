<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Lesson;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Workflow\WorkflowInterface;


/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class CommentController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("admin/comments/{id}", name="admin.comment.index")
     */
    public function index($id, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAllComments($id);
        $count = $commentRepository->countAllCommentsOfLesson($id);
        return $this->render('comment/index.html.twig', [
            'comments' => $comments,
            'count' => $count
        ]);
    }

    /**
     * @Route("admin/comment/add/{id}", name="admin.comment.add")

     */
    public function new($id,Request $request, LessonRepository $lessonRepository)
    {
        $em = $this->em;
        $comment = new Comment();
        $comment->setLesson($lessonRepository->find($id));
        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', "Le commentaire de " . $comment->getAuthor() .  "a  été créé avec succès.");
            return $this->redirectToRoute('admin.lesson.index');
        }


        return $this->render('comment/add.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("admin/{id}/edit", name="admin.comment.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.lesson.index');
        }

        return $this->render(
            'comment/edit.html.twig', [
            'comment' => $comment,
            'form'    => $form->createView()
        ]);
    }

    /**
     * @Route("admin/{id}/delete", name="admin.comment.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.lesson.index');
    }


    /**
     * @Route("admin/{id}/show", name="admin.comment.show", methods={"GET"})
     *
     */
    public function show(Comment $comment): Response
    {
        return $this->render(
            'comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("admin/{id}/change-state/{transition}", name="admin.comment.change_state", methods={"GET"})
     */
    public function changeState(Comment $comment, string $transition, WorkflowInterface $commentStateMachine, EntityManagerInterface $manager): Response
    {
        if ($commentStateMachine->can($comment, $transition)) {
            $commentStateMachine->apply($comment, $transition);
            $manager->flush();
            $this->addFlash('success', sprintf('"%s" transition applied', $transition));
        } else {
            $this->addFlash('error', sprintf('"%s" transition can\'t be applied to comment "%s"', $transition, $comment->getId()));
        }

        return $this->redirectToRoute('admin.lesson.index');
    }

}
