<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Order;
use AppBundle\Form\ConfirmType;
use AppBundle\Form\OrderType;
use AppBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    const RESULTS_PER_PAGE = 5;

    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(SearchType::class, null, [
            'action' => $this->generateUrl('home'),
        ]);

        $form->handleRequest($request);

        $params = $form->getData();

        $period = $term = null;

        if ($params !== null) {
            if (!empty($params['period'])) {
                $period = $params['period'];
            }

            if (!empty($params['term'])) {
                $term = $params['term'];
            }
        }

        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Order')
            ->search($period, $term);

        $currentPage = $request->query->getInt('page', 1);

        $pagination = $this->get('knp_paginator')->paginate($query, $currentPage, self::RESULTS_PER_PAGE);

        // calculate total pages and restrict current page
        $totalPages = ceil($pagination->getTotalItemCount() / self::RESULTS_PER_PAGE);
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm(OrderType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Order $order */
            $order = $form->getData();

            $order->setCreatedAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            $this->addFlash('base', [
                'success' => 'Order was created successfully.',
            ]);

            return $this->redirectToRoute('home');
        }

        return $this->render('default/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{order}", name="edit")
     */
    public function editAction(Order $order, Request $request)
    {
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()
                ->getManager()
                ->flush();

            $this->addFlash('base', [
                'success' => 'Order was updated successfully.',
            ]);

            return $this->redirectToRoute('home');
        }

        return $this->render('default/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{order}", name="delete")
     */
    public function deleteAction(Order $order, Request $request)
    {
        $form = $this->createForm(ConfirmType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('submitConfirm')->isClicked()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($order);
                $entityManager->flush();

                $this->addFlash('base', [
                    'success' => 'Order was deleted successfully.',
                ]);
            }

            return $this->redirectToRoute('home');
        }

        return $this->render('default/delete.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
