<?php

namespace App\Controller;

use App\Entity\ServiceJob;
use App\Form\ServiceJobType;
use App\Repository\ServiceCommandRepository;
use App\Repository\ServiceConnectionRepository;
use App\Repository\ServiceJobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        Request $request,
        EntityManagerInterface $entityManager,
        ServiceCommandRepository $serviceCommandRepository,
        ServiceConnectionRepository $serviceConnectionRepository
    ): Response {
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
        Request $request,
        ServiceJob $serviceJob,
        EntityManagerInterface $entityManager,
        ServiceCommandRepository $serviceCommandRepository,
        ServiceConnectionRepository $serviceConnectionRepository
    ): Response {
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
     * @Route("/{id}", name="service-job.delete", methods={"POST"})
     */
    public function delete(Request $request, ServiceJob $serviceJob, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serviceJob->getId(), $request->request->get('_token'))) {
            $entityManager->remove($serviceJob);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service-job.index', [], Response::HTTP_SEE_OTHER);
    }
}
