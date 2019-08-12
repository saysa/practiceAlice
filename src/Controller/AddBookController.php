<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AddBookController extends AbstractController
{
    /**
     * @Route("/add/book", name="add_book")
     *
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('add/book.html.twig', [
        ]);
    }
}