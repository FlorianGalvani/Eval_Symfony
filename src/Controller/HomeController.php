<?php

namespace App\Controller;

use App\Entity\Video;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        //find the 10 latest published videos
        $videos = $entityManager->getRepository(Video::class)->findBy([],['createdAt' => 'DESC'],10);

        return $this->render('home/index.html.twig', [
            'videos' => $videos,
        ]);
    }

    #[Route('/video/{id}', name: 'app_video_show')]
    public function showById($id, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $video = $entityManager->getRepository(Video::class)->find($id);

        return $this->render('video/index.html.twig', [
            'video' => $video,
        ]);
    }
}
