<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home/', methods: ['GET'], name: 'app_home')]
    public function index(): Response
    {        
        return new Response('{"data": "hello world"}', Response::HTTP_OK);
    }

    

    #[Route('/blog', name: 'blog_list')]
    public function list(Request $request): Response
    {
        
        // use this to get all the available attributes (not only routing ones):
        $allAttributes = $request->attributes->all();

        // ...
        return new Response(json_encode($allAttributes), Response::HTTP_OK);
    }

    
}






