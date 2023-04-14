<?php

namespace App\Service;

use App\DTO\AccountDTO;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class AccountService
{
    private $accountRepository;
    private $entityManager;
    private $paginator;

    public function __construct(AccountRepository $accountRepository, EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->accountRepository = $accountRepository;
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    public function getPaginatedAccounts(int $page, int $limit)
    {
        $query = $this->accountRepository->createQueryBuilder('a')->getQuery();
        return $this->paginator->paginate($query, $page, $limit, ['pageParameterName' => 'page']);
    }

    public function isEmailUnique(string $email): bool
    {
        $existingAccount = $this->accountRepository->findOneBy(['email' => $email]);

        return $existingAccount === null;
    }

    public function findOneByEmail(string $email): ?Account
    {
        return $this->accountRepository->findOneBy(['email' => $email]);
    }


    public function createAccount(AccountDTO $accountDTO): Account
    {
        $account = new Account();
        $account->setFirstName($accountDTO->firstName)
            ->setLastName($accountDTO->lastName)
            ->setEmail($accountDTO->email)
            ->setCompanyName($accountDTO->companyName)
            ->setPosition($accountDTO->position)
            ->setPhone1($accountDTO->phone1)
            ->setPhone2($accountDTO->phone2)
            ->setPhone3($accountDTO->phone3);
    
        $this->entityManager->persist($account);
        $this->entityManager->flush();
    
        return $account;
    }
    

    public function find(int $id): ?Account
    {
        return $this->accountRepository->find($id);
    }

    public function updateAccount(Account $account, AccountDTO $accountDTO): void
    {
        $account->setFirstName($accountDTO->firstName)
            ->setLastName($accountDTO->lastName)
            ->setEmail($accountDTO->email)
            ->setCompanyName($accountDTO->companyName)
            ->setPosition($accountDTO->position)
            ->setPhone1($accountDTO->phone1)
            ->setPhone2($accountDTO->phone2)
            ->setPhone3($accountDTO->phone3);

        $this->entityManager->flush();
    }

    public function deleteAccount(Account $account): void
    {
        $this->entityManager->remove($account);
        $this->entityManager->flush();
    }
}
