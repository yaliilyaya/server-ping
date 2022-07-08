<?php

namespace App\Controller;

use App\Entity\ServiceJob;
use App\Entity\ServiceJobReport;
use App\Enum\StatusEnum;
use App\Form\ServiceJobType;
use App\Repository\ServiceCommandRepository;
use App\Repository\ServiceConnectionRepository;
use App\Repository\ServiceJobReportRepository;
use App\Repository\ServiceJobRepository;
use App\Service\JobRunnerService;
use App\Service\ServiceJobResultSaveService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service-job")
 */
class ServiceJobController extends AbstractController
{
    /**
     * @Route("/", name="service-job.index", methods={"GET"})
     */
    public function index(ServiceJobRepository $serviceJobRepository): Response
    {
        return $this->render('service_job/index.html.twig', [
            'service_jobs' => $serviceJobRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="service-job.new", methods={"GET", "POST"})
     */
    public function new(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        ServiceCommandRepository    $serviceCommandRepository,
        ServiceConnectionRepository $serviceConnectionRepository
    ): Response
    {
        $serviceJob = new ServiceJob();
        $form = $this->createForm(ServiceJobType::class, $serviceJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $connectionId = $form->get('connectionId')->getNormData();
            $commandId = $form->get('commandId')->getNormData();

            $connection = $serviceConnectionRepository->find($connectionId);
            $command = $serviceCommandRepository->find($commandId);

            $serviceJob->setConnection($connection);
            $serviceJob->setCommand($command);

            $entityManager->persist($serviceJob);
            $entityManager->flush();

            return $this->redirectToRoute('service-job.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_job/new.html.twig', [
            'service_job' => $serviceJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="service-job.show", methods={"GET"})
     */
    public function show(ServiceJob $serviceJob): Response
    {
        return $this->render('service_job/show.html.twig', [
            'service_job' => $serviceJob,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="service-job.edit", methods={"GET", "POST"})
     */
    public function edit(
        Request                     $request,
        ServiceJob                  $serviceJob,
        EntityManagerInterface      $entityManager,
        ServiceCommandRepository    $serviceCommandRepository,
        ServiceConnectionRepository $serviceConnectionRepository
    ): Response
    {
        $form = $this->createForm(ServiceJobType::class, $serviceJob);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $connectionId = $form->get('connectionId')->getNormData();
            $commandId = $form->get('commandId')->getNormData();

            $connection = $serviceConnectionRepository->find($connectionId);
            $command = $serviceCommandRepository->find($commandId);

            $serviceJob->setConnection($connection);
            $serviceJob->setCommand($command);

            $entityManager->flush();

            return $this->redirectToRoute('service-job.index', [], Response::HTTP_SEE_OTHER);
        }

        $form->get('connectionId')
            ->setData($serviceJob->getConnection()->getId());
        $form->get('commandId')
            ->setData($serviceJob->getCommand()->getId());

        return $this->render('service_job/edit.html.twig', [
            'service_job' => $serviceJob,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/connect/{connectionId}/{commandType}", name="service-job.connect", methods={"GET", "POST"})
     */
    public function connect(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        ServiceCommandRepository    $serviceCommandRepository,
        ServiceConnectionRepository $serviceConnectionRepository,
        ServiceJobRepository        $serviceJobRepository,
                                    $connectionId,
                                    $commandType
    ): Response {
        $serviceJob = new ServiceJob();

        $form = $this->createForm(ServiceJobType::class, $serviceJob);
        $form->handleRequest($request);

        $connection = $serviceConnectionRepository->find($connectionId);
        $command = $serviceCommandRepository->findByType($commandType);
        $serviceJob->setConnection($connection);
        $serviceJob->setCommand($command);
        $serviceJob->setData($command->getData());

        $serviceJobRepository->save($serviceJob);

        $redirect = $request->get('redirect');
        if ($redirect) {
            return $this->redirectToRoute($redirect, [
                'serviceId' => $serviceJob->getConnection()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('service-job.index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="service-job.delete", methods={"POST"})
     */
    public function delete(Request $request, ServiceJob $serviceJob, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $serviceJob->getId(), $request->request->get('_token'))) {
            $entityManager->remove($serviceJob);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service-job.index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/run/{id}", name="service-job.run", methods={"GET"})
     */
    public function run(
        ServiceJob $serviceJob,
        JobRunnerService $jobRunnerService,
        ServiceJobResultSaveService $serviceJobResultSaveService
    ): Response {
        $serviceJob->setStatus(StatusEnum::DEFAULT_TYPE);
        if ($serviceJob->getStatus() !== StatusEnum::DEFAULT_TYPE) {
            return $this->json([
                'status' => $serviceJob->getStatus()
            ]);
        }

        $reports = $jobRunnerService->run($serviceJob);
        $serviceJobResultSaveService->save($serviceJob, $reports);

        return $this->json([
            'status' => $serviceJob->getStatus()
        ]);
    }



    /**
     * @Route("/readyToRun/{id}", name="service-job.ready-to-run", methods={"GET"})
     */
    public function readyToRun(
        ServiceJob $serviceJob,
        ServiceJobRepository $serviceJobRepository
    ): Response {
        $serviceJob->setStatus(StatusEnum::DEFAULT_TYPE);
        $serviceJobRepository->save($serviceJob);

        return $this->json([
            'status' => $serviceJob->getStatus()
        ]);
    }

    /**
     * @Route("/readyToRun/all/{type}", name="service-job.ready-to-run.all", methods={"GET"})
     */
    public function readyToRunAll(
        string $type,
        ServiceJobRepository $serviceJobRepository,
        ServiceCommandRepository $serviceCommandRepository
    ): Response {
        $command = $serviceCommandRepository->findByType($type);

        $jobs = $serviceJobRepository->findAllByCommand($command);
        $jobs = $jobs->filter(function (ServiceJob $job) {
            return $job->isActive();
        });
        /** @var ServiceJob $job */
        foreach ($jobs as $job) {
            $job->setStatus(StatusEnum::DEFAULT_TYPE);
        }

        $serviceJobRepository->saveAll($jobs->toArray());

        return $this->json([
            'runJobs' => 0
        ]);
    }

    /**
     * @Route("/report/{id}", name="service-job.report", methods={"GET"})
     */
    public function report(
        Request    $request,
        ServiceJob $serviceJob
    ): Response {

        echo "<pre>" . $serviceJob->getResult() . "</pre>";

        /** @var ServiceJobReport $report */
        foreach ($serviceJob->getReports() as $report) {
            echo "<pre>" . $report->getCommand() . "</pre>";
            echo "<pre>" . $report->getResult() . "</pre>";
        }

        die();
    }


}
