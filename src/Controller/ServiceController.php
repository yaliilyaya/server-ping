<?php
namespace App\Controller;

use App\Entity\ServiceCommand;
use App\Repository\ServiceConnectionRepository;
use App\Srevice\ExtractorConfigServer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
        ExtractorConfigServer $extractorConfigServer,
        Request $request,
        int $serviceId
    ): Response {
        $serviceConnection = $serviceConnectionRepository->find($serviceId);
        $config = $extractorConfigServer->extract($serviceConnection);

        if ($request->get('config')) {
            $requestConfig = $request->get('config');

            $config = array_merge(
                $config,
                json_decode($requestConfig, 1) ?: []
            );
        }

        return $this->render('index/config.html.twig', [
            'service' => $serviceConnection,
            'config' => $config
        ]);
    }
}