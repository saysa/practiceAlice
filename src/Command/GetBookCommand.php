<?php

namespace App\Command;

use App\Repository\BookRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetBookCommand extends Command
{
    protected static $defaultName = 'app:get:book';

    /**
     * @var BookRepository
     */
    private $bookRepository;

    /**
     * GetBookCommand constructor.
     *
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Get the number of books given a letter')
            ->addArgument('letter', InputArgument::REQUIRED, 'The first letter')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $books = $this->bookRepository->findBooksBeginsWith($input->getArgument('letter'));

        foreach ($books as $book) {
            $output->writeln('<info>'.$book->getTitle().'</info>');
        }
    }
}
