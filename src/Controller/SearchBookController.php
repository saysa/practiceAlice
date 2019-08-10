<?php

namespace App\Controller;

use App\Form\SearchBookType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchBookController extends AbstractController
{
    /**
     * @Route("/search/book", name="search_book")
     */
    public function index(Request $request, BookRepository $book_repository)
    {
        $form = $this->createForm(SearchBookType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $criteria = $form->getData();
            $books = $book_repository->searchBooks($criteria);

            dd($books);
        }

        return $this->render('search/book.html.twig', [
            'searchForm' => $form->createView(),
        ]);
    }
}
