<?php

namespace App\Controller;

use App\Entity\Duck;
use App\Form\DuckType;
use App\Repository\DuckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/duck')]
final class DuckController extends AbstractController
{

    #[Route(name: 'app_duck_index', methods: ['GET'])]
    public function index(DuckRepository $duckRepository): Response
    {
        return $this->render('duck/index.html.twig', [
            'ducks' => $duckRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_duck_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $duck = new Duck();
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($duck);
            $entityManager->flush();

            return $this->redirectToRoute('app_duck_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('duck/new.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }

    #[Route('/current', name: 'app_duck_show', methods: ['GET'])]
    public function show(): Response
    {
        $duck = $this->getUser();
        return $this->render('duck/show.html.twig', [
            'duck' => $duck,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_duck_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Duck $duck, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DuckType::class, $duck);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_duck_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('duck/edit.html.twig', [
            'duck' => $duck,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_duck_delete', methods: ['POST'])]
    public function delete(Request $request, Duck $duck, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$duck->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($duck);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quack_index', [], Response::HTTP_SEE_OTHER);
    }
}
