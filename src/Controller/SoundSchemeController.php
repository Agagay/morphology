<?php

namespace App\Controller;

use App\Service\SoundSchemeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sound')]
class SoundSchemeController extends AbstractController
{
    #[Route('/scheme', name: 'app__sound_scheme')]
    public function index(SoundSchemeService $soundSchemeService): Response
    {
        $res = $soundSchemeService->parse('Кабан упал и лапу набок');

        return new JsonResponse(['res' => $res]);
    }
}
