<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{

    /* To display a list of accounts with paginated navigation */
    /**
     * @Route("/", name="account_index", methods={"GET"})
     */
    public function index(AccountRepository $accountRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $accountRepository->createQueryBuilder('a')->getQuery();
        $page = $request->query->getInt('page', 1);
        $limit = 10;
    
        $accounts = $paginator->paginate($query, $page, $limit, ['pageParameterName' => 'page']);
        $haveToPaginate = $accounts->getTotalItemCount() > $accounts->getItemNumberPerPage();

        return $this->render('account/index.html.twig', [
            'accounts' => $accounts,
            'haveToPaginate' => $haveToPaginate,
        ]);
    }

    /* To create new account */
    /**
     * @Route("/new", name="account_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/new.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /* To edit account */
    /**
     * @Route("/{id}/edit", name="account_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(int $id, Request $request, AccountRepository $accountRepository, EntityManagerInterface $entityManager): Response
    {
        $account = $accountRepository->find($id);

        if (!$account) {
            throw $this->createNotFoundException('Account not found');
        }

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/edit.html.twig', [
            'account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /* To delete account */
    /**
     * @Route("/{id}", name="account_delete", methods={"POST"})
     */
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $account = $entityManager->getRepository(Account::class)->find($id);

        if ($account) {
            $entityManager->remove($account);
            $entityManager->flush();
        }

        return $this->redirectToRoute('account_index');
    }


}
