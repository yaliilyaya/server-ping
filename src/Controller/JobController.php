<?php
namespace App\Controller;

use App\Entity\ServiceCommand;
use App\Entity\ServiceConnection;
use App\Entity\ServiceJob;
use App\Repository\ServiceCommandRepository;
use App\Repository\ServiceConnectionRepository;
use Doctrine\Common\Collections\Collection;
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
     * @param ServiceCommandRepository $serviceCommandRepository
     * @param int $serviceId
     * @return Response
     */
    public function job(
        ServiceConnectionRepository $serviceConnectionRepository,
        ServiceCommandRepository $serviceCommandRepository,
        int $serviceId
    ): Response {
        /** @var ServiceConnection $serviceConnection */
        $serviceConnection = $serviceConnectionRepository->find($serviceId);
        $commandIds = $this->findCommandIds($serviceConnection->getJobs());

        $commands = $serviceCommandRepository->findAllIgnoreIds($commandIds);

        return $this->render('index/jobs.html.twig', [
            'service' => $serviceConnection,
            'jobs' => $serviceConnection->getJobs(),
            'commands' => $commands
        ]);
    }

    /**
     * @param Collection $jobs
     * @return int[]
     */
    private function findCommandIds(Collection $jobs): array
    {
        return $jobs->map(function (ServiceJob $job) {
            return $job->getCommand()->getId();
        })->toArray();
    }
}