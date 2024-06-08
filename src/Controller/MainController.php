<?php

namespace App\Controller; // directory structure

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response; // import class to route
use Symfony\Component\Routing\Attribute\Route; // import class to response the user
use App\Model\Teacher;

class MainController extends AbstractController// same as file name
{
    #[Route('/')] // route from url to function
    public function homepage(): Response // what it returns
    {// build and return (content(JSON) + status)
        // dd("I am here"); // dump and die command
        return new Response('{"data": "hello world"}', Response::HTTP_OK);
    }

    #[Route('/api/teachers')]
    public function getTeachers(): Response // what it returns
    {
        // array type
        // $teachers = [
        //     [
        //         'name' => 'jose',
        //         'class' => 'dam',
        //         'status' => 'active',
        //     ],
        //     [
        //         'name' => 'javier',
        //         'class' => 'dam',
        //         'status' => 'inactive',
        //     ],
        //     [
        //         'name' => 'cristian',
        //         'class' => 'daw',
        //         'status' => 'active',
        //     ],
        // ];
        // object type
        $teachers = [
            new Teacher(
                1,
                'jose',
                'dam',
                'active'
            ),
            new Teacher(
                2,
                'javier',
                'dam',
                'inactive',
            ),
            new Teacher(
                3,
                'cristian',
                'daw',
                'active',
            ),
        ];

        return $this->json($teachers);
    }
}
