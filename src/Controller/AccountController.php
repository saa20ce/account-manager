<?php

namespace App\Controller;

use App\DTO\AccountDTO;
use App\Form\AccountType;
use App\Service\AccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account_index", methods={"GET"})
     */
    public function index(Request $request, AccountService $accountService): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;

        $accounts = $accountService->getPaginatedAccounts($page, $limit);
        $haveToPaginate = $accounts->getTotalItemCount() > $accounts->getItemNumberPerPage();

        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
            'haveToPaginate' => $haveToPaginate,
        ]);
    }

    /**
     * @Route("/new", name="account_new", methods={"GET","POST"})
     */

    public function new(Request $request, AccountService $accountService): Response
    {
        $accountDto = new AccountDTO();
        $form = $this->createForm(AccountType::class, $accountDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check mail unique
            if (!$accountService->isEmailUnique($accountDto->email)) {
                $form->get('email')->addError(new FormError('This email address is already in use.'));
            } else {
                $account = $accountService->createAccount($accountDto);

                return $this->redirectToRoute('account_index');
            }
        }

        return $this->render('account/new.html.twig', [
            'account' => $accountDto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="account_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(int $id, Request $request, AccountService $accountService): Response
    {
        $account = $accountService->find($id);

        if (!$account) {
            throw $this->createNotFoundException('Account not found');
        }

        $accountDTO = AccountDTO::createFromEntity($account);
        $form = $this->createForm(AccountType::class, $accountDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Проверка уникальности email
            $existingAccount = $accountService->findOneByEmail($accountDTO->email);
            if ($existingAccount && $existingAccount->getId() !== $id) {
                $form->get('email')->addError(new FormError('This email is already in use.'));
            } else {
                $accountService->updateAccount($account, $accountDTO);
                return $this->redirectToRoute('account_index');
            }
        }

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="account_delete", methods={"POST"})
     */
    public function delete(int $id, AccountService $accountService): Response
    {
        $account = $accountService->find($id);

        if ($account) {
            $accountService->deleteAccount($account);
        }

        return $this->redirectToRoute('account_index');
    }
}
