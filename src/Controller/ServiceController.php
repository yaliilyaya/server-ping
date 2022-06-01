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
class ServiceController extends AbstractController
{
    /**
     * @Route("/config/{serviceId}", name="config")
     * @return Response
     */
    public function config(
        ServiceConnectionRepository $serviceConnectionRepository,
        int $serviceId
    ): Response {

        $serviceConnection = $serviceConnectionRepository->find($serviceId);
        $config = [];

        return $this->render('index/config.html.twig', [
            'service' => $serviceConnection
        ]);
    }
}