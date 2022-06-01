<?php
namespace App\Controller;

use App\Entity\ServiceCommand;
use App\Entity\ServiceConnection;
use App\Repository\ServiceConnectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 * @see Route
 */
class JobController extends AbstractController
{
    /**
     * @Route("/jobs/{serviceId}", name="jobs")
     * @param ServiceConnectionRepository $serviceConnectionRepository
     * @param int $serviceId
     * @return Response
     */
    public function job(
        ServiceConnectionRepository $serviceConnectionRepository,
        int $serviceId
    ): Response {
        /** @var ServiceConnection $serviceConnection */
        $serviceConnection = $serviceConnectionRepository->find($serviceId);

        return $this->render('index/jobs.html.twig', [
            'service' => $serviceConnection,
            'jobs' => $serviceConnection->getJobs()
        ]);
    }
}