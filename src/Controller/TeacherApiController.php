<?php

namespace App\Controller; // directory structure

use App\Model\Teacher;
use App\Repository\TeacherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // import class to route
use Symfony\Component\HttpFoundation\Response; // import class to response the user
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/teachers')]
class TeacherApiController extends AbstractController // same as file name
{
    #[Route('/home')] // route from url to function
    public function homepage(): Response // what it returns
    {// build and return (content(JSON) + status)
        // dd("I am here"); // dump and die command
        return new Response('{"data": "hello world"}', Response::HTTP_OK);
    }

    #[Route('', methods: ['GET'], name: 'app_teacherapi_getAll')]
    public function getAll(TeacherRepository $repository): Response // what it returns
    {
        // dd($logger);
        // $logger->info('before build objects line');
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
        // $teachers = [
        //     new Teacher(
        //         1,
        //         'jose',
        //         'dam',
        //         'active'
        //     ),
        //     new Teacher(
        //         2,
        //         'javier',
        //         'dam',
        //         'inactive',
        //     ),
        //     new Teacher(
        //         3,
        //         'cristian',
        //         'daw',
        //         'active',
        //     ),
        // ];
        $teachers = $repository->findAll();

        return $this->json($teachers);
    }

    #[Route('/{id<\d+>}', methods: ['GET'])] // regex to control only digit input
    public function get(int $id, TeacherRepository $repository): Response
    {
        // dd($id);
        $teacher = $repository->find($id);

        if (!$teacher) {
            throw $this->createNotFoundException('Teacher not found');
        }

        return $this->json($teacher);
    }
}
