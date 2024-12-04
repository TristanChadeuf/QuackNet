<?php

namespace App\Controller;

use App\Entity\CoinCoin;
use App\Entity\Duck;
use App\Form\CoinCoinType;
use App\Form\CommentType;
use App\Form\DuckType;
use App\Repository\CoinCoinRepository;
use App\Service\UploaderQuackPicture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/coincoin')]
final class CoinCoinController extends AbstractController
{
    #[Route(name: 'app_coin_coin_index', methods: ['GET'])]
    public function index(CoinCoinRepository $coinCoinRepository): Response
    {
        return $this->render('coin_coin/index.html.twig', [
            'coin_coins' => $coinCoinRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_coin_coin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SluggerInterface $slugger, UploaderQuackPicture $uploaderQuackPicture,EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $product = new CoinCoin();
        $form = $this->createForm(CoinCoinType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $picture = $form->get('picture')->getData();

            if ($picture) {
                $uploadedPicturePath = $uploaderQuackPicture->uploadQuackImage($picture);
                $product->setPicture($uploadedPicturePath);
            }

            $product->setAuthor($user);

            $em->persist($product);
            $em->flush();


            return $this->redirectToRoute('app_coin_coin_index');
        }

        return $this->render('coin_coin/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coin_coin_show', methods: ['GET'])]
    public function show(CoinCoin $coinCoin): Response
    {
        return $this->render('coin_coin/show.html.twig', [
            'coin_coin' => $coinCoin,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_coin_coin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoinCoin $coinCoin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoinCoinType::class, $coinCoin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_coin_coin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coin_coin/edit.html.twig', [
            'coin_coin' => $coinCoin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_coin_coin_delete', methods: ['POST'])]
    public function delete(Request $request, CoinCoin $coinCoin, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coinCoin->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($coinCoin);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_coin_coin_index', [], Response::HTTP_SEE_OTHER);
    }
}
