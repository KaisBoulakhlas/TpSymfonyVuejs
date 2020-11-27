<?php


namespace App\Service;
use App\Repository\LessonRepository;

class LessonManager
{

  private LessonRepository $lessonRepository;
    /**
     * LessonManager constructor.
     * @param LessonRepository $lessonRepository
     */
    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    public function getLessons()
    {
        return $this->lessonRepository->findAll();
    }
}