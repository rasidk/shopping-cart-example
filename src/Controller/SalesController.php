<?php

namespace App\Controller;

use App\Entity\Sales;
use App\Form\SalesType;
use App\Repository\SalesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sales")
 */
class SalesController extends AbstractController
{
    /**
     * @Route("/", name="app_sales_index", methods={"GET"})
     */
    public function index(SalesRepository $salesRepository): Response
    {
        return $this->render('sales/index.html.twig', [
            'sales' => $salesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_sales_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SalesRepository $salesRepository): Response
    {
        $sale = new Sales();
        $form = $this->createForm(SalesType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salesRepository->add($sale);
            return $this->redirectToRoute('app_sales_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sales/new.html.twig', [
            'sale' => $sale,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_sales_show", methods={"GET"})
     */
    public function show(Sales $sale): Response
    {
        return $this->render('sales/show.html.twig', [
            'sale' => $sale,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sales_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sales $sale, SalesRepository $salesRepository): Response
    {
        $form = $this->createForm(SalesType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salesRepository->add($sale);
            return $this->redirectToRoute('app_sales_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sales/edit.html.twig', [
            'sale' => $sale,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_sales_delete", methods={"POST"})
     */
    public function delete(Request $request, Sales $sale, SalesRepository $salesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sale->getId(), $request->request->get('_token'))) {
            $salesRepository->remove($sale);
        }

        return $this->redirectToRoute('app_sales_index', [], Response::HTTP_SEE_OTHER);
    }
}
