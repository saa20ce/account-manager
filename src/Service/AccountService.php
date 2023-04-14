<?php

// src/Service/AccountService.php

namespace App\Service;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class AccountService
{
    private $entityManager;
    private $accountRepository;
    private $paginator;

    public function __construct(
        EntityManagerInterface $entityManager,
        AccountRepository $accountRepository,
        PaginatorInterface $paginator
    ) {
        $this->entityManager = $entityManager;
        $this->accountRepository = $accountRepository;
        $this->paginator = $paginator;
    }

    public function find(int $id): ?Account
    {
        return $this->accountRepository->find($id);
    }
    
    public function getPaginatedAccounts(int $page, int $limit)
    {
        $query = $this->accountRepository->createQueryBuilder('a')->getQuery();
        return $this->paginator->paginate($query, $page, $limit, ['pageParameterName' => 'page']);
    }

    public function createAccount(Account $account)
    {
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    }

    public function updateAccount()
    {
        $this->entityManager->flush();
    }

    public function deleteAccount(Account $account)
    {
        $this->entityManager->remove($account);
        $this->entityManager->flush();
    }
}
