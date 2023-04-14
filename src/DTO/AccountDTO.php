<?php
// src/DTO/AccountDTO.php

namespace App\DTO;
use App\Entity\Account;
use Symfony\Component\Validator\Constraints as Assert;

class AccountDTO
{
    public ?int $id = null;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    public string $firstName;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    public string $lastName;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email;

    /**
     * @Assert\Length(max=255)
     */
    public ?string $companyName = null;

    /**
     * @Assert\Length(max=255)
     */
    public ?string $position = null;

    /**
     * @Assert\Length(min=10, max=10, exactMessage="Phone number must be 10 digits.")
     */
    public ?string $phone1 = null;

    /**
     * @Assert\Length(min=10, max=10, exactMessage="Phone number must be 10 digits.")
     */
    public ?string $phone2 = null;

    /**
     * @Assert\Length(min=10, max=10, exactMessage="Phone number must be 10 digits.")
     */
    public ?string $phone3 = null;



    public static function createFromEntity(Account $account): AccountDTO
    {
        $accountDTO = new AccountDTO();
        $accountDTO->id = $account->getId();
        $accountDTO->firstName = $account->getFirstName();
        $accountDTO->lastName = $account->getLastName();
        $accountDTO->email = $account->getEmail();
        $accountDTO->companyName = $account->getCompanyName();
        $accountDTO->position = $account->getPosition();
        $accountDTO->phone1 = $account->getPhone1();
        $accountDTO->phone2 = $account->getPhone2();
        $accountDTO->phone3 = $account->getPhone3();

        return $accountDTO;
    }



}
