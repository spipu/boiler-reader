<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BufferRepository;
use App\Ui\BufferForm;
use App\Ui\BufferGrid;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Spipu\UiBundle\Exception\UiException;
use Spipu\UiBundle\Service\Ui\GridFactory;
use Spipu\UiBundle\Service\Ui\ShowFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class BufferController extends AbstractController
{
    /**
     * @throws UiException
     */
    #[Route(path: '/buffer/', name: 'app_buffer_list', methods: ['GET'])]
    public function list(GridFactory $gridFactory, BufferGrid $bufferGrid): Response
    {
        $manager = $gridFactory->create($bufferGrid);
        $manager->setRoute('app_buffer_list');
        $manager->validate();

        return $this->render('buffer/list.html.twig', ['manager' => $manager]);
    }

    /**
     * @throws UiException
     */
    #[Route(path: '/buffer/show/{id}', name: 'app_buffer_show', methods: ['GET'])]
    public function show(
        ShowFactory $showFactory,
        BufferForm $bufferForm,
        BufferRepository $bufferRepository,
        int $id
    ): Response {
        $resource = $bufferRepository->findOneBy(['id' => $id]);
        if (!$resource) {
            throw $this->createNotFoundException();
        }

        $manager = $showFactory->create($bufferForm);
        $manager->setResource($resource);
        $manager->validate();

        return $this->render('buffer/show.html.twig', ['manager' => $manager]);
    }

    #[Route(path: '/buffer/delete/{id}', name: 'app_buffer_delete', methods: ['GET'])]
    public function delete(
        BufferRepository $bufferRepository,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        $resource = $bufferRepository->findOneBy(['id' => $id]);
        if (!$resource) {
            throw $this->createNotFoundException();
        }

        try {
            $entityManager->remove($resource);
            $entityManager->flush();
            $this->addFlash('success', 'Buffer deleted');
        } catch (Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->redirectToRoute('app_buffer_list');
    }
}
