<?php
namespace App\Controller;

use App\Entity\ServiceCommand;
use App\Enum\StatusEnum;
use App\Repository\ServiceConnectionRepository;
use App\Srevice\ExtractorConfigServer;
use App\Srevice\SaveConfigServer;
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
     * @Route("/newConnection", name="new.connection")
     * @param ServiceConnectionRepository $serviceConnectionRepository
     * @return Response
     */
    public function newConnection(
        ServiceConnectionRepository $serviceConnectionRepository
    ): Response {
        $service = $serviceConnectionRepository->create();
        $service->setIp('127.0.0.1');
        $service->setName('new connection');
        $service->setStatus(StatusEnum::INFO_TYPE);
        $serviceConnectionRepository->save($service);

        return $this->redirectToRoute('config', [
            'serviceId' => $service->getId()
        ]);
    }

    /**
     * @Route("/delete/{serviceId}", name="delete")
     * @param int $serviceId
     * @return Response
     */
    public function delete(
        ServiceConnectionRepository $serviceConnectionRepository,
        int $serviceId
    ): Response
    {
        $serviceConnection = $serviceConnectionRepository->find($serviceId);
        $serviceConnectionRepository->delete($serviceConnection);

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/config/{serviceId}", name="config")
     * @param ServiceConnectionRepository $serviceConnectionRepository
     * @param ExtractorConfigServer $extractorConfigServer
     * @param SaveConfigServer $saveConfigServer
     * @param Request $request
     * @param int $serviceId
     * @return Response
     */
    public function config(
        ServiceConnectionRepository $serviceConnectionRepository,
        ExtractorConfigServer $extractorConfigServer,
        SaveConfigServer $saveConfigServer,
        Request $request,
        int $serviceId
    ): Response {
        $serviceConnection = $serviceConnectionRepository->find($serviceId);
        $config = $extractorConfigServer->extract($serviceConnection);

        if ($request->get('config')) {
            $requestConfig = $request->get('config');
            $successSave = $saveConfigServer->save(
                $serviceConnection,
                $config,
                json_decode($requestConfig, true) ?: []
            );
            if ($successSave) {
                die(__FILE__);
            }
        }

        return $this->render('index/config.html.twig', [
            'service' => $serviceConnection,
            'config' => $config
        ]);
    }
}