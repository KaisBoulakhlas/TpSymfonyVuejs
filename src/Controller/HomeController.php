<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Lesson;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class HomeController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class HomeController extends AbstractController
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(LessonRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $datas = $repository->findAllLessons();
        $lessons = $paginator->paginate( $datas, $request->query->getInt('page', 1), 7);
        return $this->render('home/index.html.twig', [
            'lessons' => $lessons
        ]);
    }

    /**
     * @Route("/lesson/{slug}", name="home.lesson.show")
     */
    public function show($slug,CommentRepository $commentRepository,LessonRepository $lessonRepository, PaginatorInterface $paginator, Request $request) : Response
    {
        $em = $this->em;
        $lesson =  $lessonRepository->findOneBy(['slug' => $slug]);
        $datas = $commentRepository->findAllCommentsPublished($lesson->getId());
        $comments = $paginator->paginate($datas, $request->query->getInt('page', 1), 30);
        $count = $commentRepository->countAllCommentsOfLessonPublished($lesson->getId());
        $comment = new Comment();
        $comment->setLesson($lesson);

        $form = $this->createForm(CommentType::class,$comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', "Le commentaire de " . $comment->getAuthor() .  "a  été créé avec succès.");
            return $this->redirectToRoute('home');

        }
        return $this->render('home/show.html.twig', [
            'lesson' => $lesson,
            'count' => $count,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }




}
