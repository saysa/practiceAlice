<?php


namespace App\Controller;



use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditBookController extends AbstractController
{
    /**
     * @Route("/edit/book/{id}", name="edit_book")
     * @return Response
     */
    public function edit(Request $request, BookRepository $repository)
    {
        $book = $repository->find($request->attributes->get('id'));

        $this->denyAccessUnlessGranted('edit', $book);

        return new Response('edit book');
    }
}