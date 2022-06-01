<?php
namespace App\Controller;

use App\Entity\ServiceCommand;
use App\Repository\ServiceConnectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @see Route
 */
class CommandController extends AbstractController
{
    /**
     * @Route("/commands/{serviceId}", name="commands")
     * @param ServiceConnectionRepository $serviceConnectionRepository
     * @param int $serviceId
     * @return Response
     */
    public function commands(
        ServiceConnectionRepository $serviceConnectionRepository,
        int $serviceId
    ): Response {

        $serviceConnection = $serviceConnectionRepository->find($serviceId);
        $commands = [
            new ServiceCommand()
        ];

        return $this->render('index/commands.html.twig', [
            'service' => $serviceConnection,
            'commands' => $commands
        ]);
    }
}