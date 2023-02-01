<?php

namespace App\Controller\Api;

use App\Entity\Video;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VideoController extends AbstractController
{

    #[Route('/api/video/create', name: 'app_video_create',)]
    public function create(Request $request,ManagerRegistry $doctrine): Response
    {
        
        $response = [
            'success' => false,
        ];

        if(!$request->isXmlHttpRequest()) {
            return $this->json($response,Response::HTTP_UNAUTHORIZED);
        }

        $errors = [];
        $content = json_decode($request->getContent());

        if (empty($content->name)) {
            $errors['name'] = 'Name is required';
        }

        if (empty($content->synopsis)) {
            $errors['synopsis'] = 'Synopsis is required';
        }

        if (empty($content->type)) {
            $errors['type'] = 'Type is required';
        }

        
        if (count($errors) == 0) {
            $response['success'] = true;
            $entityManager = $doctrine->getManager();

            $video = new Video();
            $video->setName($content->name);
            $video->setSynopsis($content->synopsis);
            $video->setType($content->type);
            $video->setCreatedAt(new \DateTimeImmutable());
    
            $entityManager->persist($video);
            $entityManager->flush();
        } else {
            $response['errors'] = $errors;
        }

        return $this->json($response, Response::HTTP_OK);
    }

    #[Route('/api/video/getall', name: 'app_video_getall')]
    public function getAll(ManagerRegistry $doctrine, Request $request): Response
    {
        if(!$request->isXmlHttpRequest()) {
            return $this->json('You are not allowed to access this ressources',Response::HTTP_UNAUTHORIZED);
        }

        $entityManager = $doctrine->getManager();

        $videos = $entityManager->getRepository(Video::class)->findAll();

        return $this->json($videos, Response::HTTP_OK);
    }

    #[Route('/api/video/{id}', name: 'app_video_get')]
    public function get($id, ManagerRegistry $doctrine, Request $request): Response
    {

        if(!$request->isXmlHttpRequest()) {
            return $this->json('You are not allowed to access this ressources',Response::HTTP_UNAUTHORIZED);
        }

        $entityManager = $doctrine->getManager();

        $video = $entityManager->getRepository(Video::class)->find($id);

        if ($video == null) {
            return $this->json('The video with id : ' . $id . ' cannot been found',Response::HTTP_NOT_FOUND);
        }

        return $this->json($video, Response::HTTP_OK);
    }

}
