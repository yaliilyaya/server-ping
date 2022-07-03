<?php

namespace App\Controller;

use App\Entity\ServiceCommand;
use App\Entity\ServiceJob;
use App\Enum\StatusEnum;
use App\Form\ServiceCommandType;
use App\Repository\ServiceCommandRepository;
use App\Repository\ServiceJobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service-command")
 * @see Route
 */
class ServiceCommandController extends AbstractController
{
    /**
     * @Route("/", name="service-command.index", methods={"GET"})
     */
    public function index(ServiceCommandRepository $serviceCommandRepository): Response
    {
        return $this->render('service_command/index.html.twig', [
            'service_commands' => $serviceCommandRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="service-command.new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceCommand = new ServiceCommand();
        $form = $this->createForm(ServiceCommandType::class, $serviceCommand);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceCommand);
            $entityManager->flush();

            return $this->redirectToRoute('service-command.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_command/new.html.twig', [
            'service_command' => $serviceCommand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="service-command.show", methods={"GET"})
     */
    public function show(ServiceCommand $serviceCommand): Response
    {
        return $this->render('service_command/show.html.twig', [
            'service_command' => $serviceCommand,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="service-command.edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ServiceCommand $serviceCommand, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceCommandType::class, $serviceCommand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('service-command.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_command/edit.html.twig', [
            'service_command' => $serviceCommand,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete/", name="service-command.delete", methods={"POST"})
     */
    public function delete(Request $request, ServiceCommand $serviceCommand, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serviceCommand->getId(), $request->request->get('_token'))) {
            $entityManager->remove($serviceCommand);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service-command.index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/job-reports/{type}", name="service-command.job.reports")
     * @param string $type
     * @param ServiceJobRepository $serviceJobRepository
     * @param ServiceCommandRepository $serviceCommandRepository
     * @return Response
     */
    public function jobReport(
        string                   $type,
        ServiceJobRepository     $serviceJobRepository,
        ServiceCommandRepository $serviceCommandRepository,
        StatusEnum $statusEnum
    ): Response {

        $command = $serviceCommandRepository->findByType($type);
        $jobs = $serviceJobRepository->findAllByCommand($command);
        $lastStatus = $this->findLastStatus($jobs, $statusEnum);

        return $this->render('service_command/jobs.report.html.twig', [
            'jobs' => $jobs,
            'commandType' => $type,
            'command' => $command,
            'lastStatus' => $lastStatus
        ]);
    }

    /**
     * @param ArrayCollection $jobs
     * @param $statusEnum
     * @return string
     */
    private function findLastStatus(ArrayCollection $jobs, $statusEnum): string
    {
        /** @var string[] $statues */
        $statues = $jobs->map(function(ServiceJob $job) {
            return $job->getStatus();
        })->toArray();

        usort($statues, function ($status1, $status2) use ($statusEnum)
        {
            return $statusEnum->getSortWeight($status2) - $statusEnum->getSortWeight($status1);
        });

        return current($statues) ?: StatusEnum::DEFAULT_TYPE;
    }
}
