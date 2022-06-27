<?php
namespace App\Controller;

use App\Repository\ServiceConnectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @see Route
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param ServiceConnectionRepository $serviceConnectionRepository
     * @return Response
     */
    public function index(ServiceConnectionRepository $serviceConnectionRepository): Response
    {
        $services = $serviceConnectionRepository->findActiveAll();

        return $this->render('index/dashboard.html.twig', [
            'services' => $services
        ]);
    }
}