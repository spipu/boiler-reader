<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Repository\BufferRepository;
use App\Ui\BufferForm;
use App\Ui\BufferGrid;
use Spipu\UiBundle\Exception\UiException;
use Spipu\UiBundle\Service\Ui\GridFactory;
use Spipu\UiBundle\Service\Ui\ShowFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     * @param GridFactory $gridFactory
     * @param BufferGrid $bufferGrid
     * @return Response
     * @throws UiException
     */
    public function home(GridFactory $gridFactory, BufferGrid $bufferGrid): Response
    {
        $manager = $gridFactory->create($bufferGrid);
        $manager->setRoute('app_home');
        $manager->validate();

        return $this->render(
            'main/list.html.twig',
            [
                'manager' => $manager
            ]
        );
    }

    /**
     * @Route("/show/{id}", name="app_buffer_show", methods="GET")
     * @param ShowFactory $showFactory
     * @param BufferForm $bufferForm
     * @param BufferRepository $bufferRepository
     * @param int $id
     * @return Response
     * @throws UiException
     */
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

        return $this->render(
            'main/show.html.twig',
            [
                'manager' => $manager
            ]
        );
    }
}
