<?php

namespace App\Repository;

use App\Model\Teacher;
use App\Model\TeacherStatusEnum;
use Psr\Log\LoggerInterface;

class TeacherRepository
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function findAll(): array
    {
        $this->logger->info('before build objects line');

        return [
            new Teacher(
                1,
                'jose',
                'dam',
                TeacherStatusEnum::IN_PROGRESS
            ),
            new Teacher(
                2,
                'javier',
                'dam',
                TeacherStatusEnum::WAITING
            ),
            new Teacher(
                3,
                'cristian',
                'daw',

                TeacherStatusEnum::COMPLETED
            ),
        ];
    }

    public function find(int $id): ?Teacher // object or null
    {
        foreach ($this->findAll() as $teacher) {
            if ($teacher->getId() === $id) {
                return $teacher;
            }
        }

        return null;
    }
}
