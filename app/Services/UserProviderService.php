<?php

declare(strict_types=1);


namespace App\Services;

use App\Contracts\UserInterface;
use App\Contracts\UserProviderServiceInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

class UserProviderService implements UserProviderServiceInterface
{

    public function __construct(public readonly EntityManager $entityManager)
    {
        # code...
    }

    public function getById(int $id): ?UserInterface
    {

        return $this->entityManager->find(User::class, $id);
    }
    public function getByCredentials(array $credentials): ?UserInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);
    }
}
