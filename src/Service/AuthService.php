<?php

declare(strict_types=1);

namespace src\Service;

use Doctrine\ORM\EntityManager;

class AuthService
{
    private EntityManager $em;
    private SessionService $session;

    public function __construct(EntityManager $em, SessionService $session)
    {
        $this->em = $em;
        $this->session = $session;
    }

    public function login(string $email, string $password): bool
    {
        // Placeholder - implement with your User entity
        $user = $this->em->getRepository(\src\Model\User::class)->findOneBy(['email' => $email]);
        
        if ($user && password_verify($password, $user->getPassword())) {
            $this->session->set('user_id', $user->getId());
            $this->session->set('user_email', $user->getEmail());
            return true;
        }
        
        return false;
    }

    public function logout(): void
    {
        $this->session->clear();
        $this->session->regenerate();
    }

    public function isAuthenticated(): bool
    {
        return $this->session->has('user_id');
    }

    public function getCurrentUser(): ?int
    {
        return $this->session->get('user_id');
    }
}
