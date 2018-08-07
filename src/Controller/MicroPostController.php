<?php

namespace App\Controller;

use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/micro-post")
 * Class MicroPostController
 * @package App\Controller
 */
class MicroPostController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;

    public function __construct(\Twig_Environment $twig, MicroPostRepository $microPostRepository)
    {
        $this->twig = $twig;
        $this->microPostRepository = $microPostRepository;
    }

    /**
     * @Route("/", name="micro_post_index")
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(): Response
    {
        $html = $this->twig->render('micro-post/index.html.twig', [
            'posts' => $this->microPostRepository->findAll(),
        ]);
        return new Response($html);
    }
}
