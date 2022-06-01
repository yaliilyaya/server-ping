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
     * @return Response
     */
    public function commands(ServiceConnectionRepository $serviceConnectionRepository): Response
    {
        $commands = [
            new ServiceCommand()
        ];

        return $this->render('index/commands.html.twig', [
            'commands' => $commands
        ]);
    }
}