<?php

namespace App\Controller;

use App\Entity\ServiceConnection;
use App\Form\ServiceConnectionType;
use App\Repository\ServiceConnectionRepository;
use App\Repository\ServiceJobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service-connection")
 */
class ServiceConnectionController extends AbstractController
{
    /**
     * @Route("/", name="service-connection.index", methods={"GET"})
     */
    public function index(ServiceConnectionRepository $serviceConnectionRepository): Response
    {
        return $this->render('service_connection/index.html.twig', [
            'service_connections' => $serviceConnectionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="service-connection.new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $serviceConnection = new ServiceConnection();
        $form = $this->createForm(ServiceConnectionType::class, $serviceConnection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceConnection);
            $entityManager->flush();

            return $this->redirectToRoute('service-connection.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_connection/new.html.twig', [
            'service_connection' => $serviceConnection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="service-connection.show", methods={"GET"})
     */
    public function show(ServiceConnection $serviceConnection): Response
    {
        return $this->render('service_connection/show.html.twig', [
            'service_connection' => $serviceConnection,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="service-connection.edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ServiceConnection $serviceConnection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceConnectionType::class, $serviceConnection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('service-connection.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_connection/edit.html.twig', [
            'service_connection' => $serviceConnection,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="service-connection.delete", methods={"POST"})
     */
    public function delete(
        Request $request,
        ServiceConnection $serviceConnection,
        EntityManagerInterface $entityManager,
        ServiceJobRepository $serviceJobRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $serviceConnection->getId(), $request->request->get('_token'))) {
            $serviceJobRepository->removeAll($serviceConnection->getJobs());
            $entityManager->remove($serviceConnection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('service-connection.index', [], Response::HTTP_SEE_OTHER);
    }
}
