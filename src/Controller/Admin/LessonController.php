<?php

namespace App\Controller\Admin;

use App\Entity\Lesson;
use App\Form\LessonType;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class LessonController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("admin/lesson", name="admin.lesson.index")
     */
    public function index(LessonRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $datas = $repository->findAllLessons();
        $lessons = $paginator->paginate( $datas, $request->query->getInt('page', 1), 7);
        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessons,
        ]);
    }

    /**
     * @Route("admin/lessons/{slug}", name="admin.lesson.show")
     */
    public function show($slug,LessonRepository $lessonRepository) : Response
    {
        $lesson =  $lessonRepository->findOneBy(['slug' => $slug]);
        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson
        ]);
    }

    /**
     * @Route("admin/lesson/create", name="admin.lesson.add" , methods="GET|POST")
     */
    public function new(Request $request)
    {
        $lesson = new Lesson();
        $em = $this->em;

        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($lesson);
            $em->flush();
            $this->addFlash('success', "La leçon ". $lesson->getTitle() . "a été créée avec succès.");
            return $this->redirectToRoute('admin.lesson.index');
        }

        return $this->render('lesson/add.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/lesson/edit/{id}", name="admin.lesson.edit", methods="GET|POST")
     */
    public function edit($id, Request $request,LessonRepository $repository)
    {
        $em = $this->em;
        $lesson = $repository->find($id);
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', "La leçon ". $lesson->getTitle() . " a été modifié avec succès.");
            return $this->redirectToRoute('admin.lesson.index');
        };

        return $this->render('lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/lesson/delete/{id}", name="admin.lesson.delete", methods="DELETE")
     */
    public function delete($id, Request $request, LessonRepository $repository)
    {
        $em = $this->em;
        $lesson = $repository->find($id);
        if($this->isCsrfTokenValid('delete' . $lesson->getId(), $request->request->get('_token'))){
            $em->remove($lesson);
            $em->flush();
            $this->addFlash('success', "La leçon " . $lesson->getTitle() . " est supprimé avec succès.");
        }

        return $this->redirectToRoute('admin.lesson.index');
    }
}
