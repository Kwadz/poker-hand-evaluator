<?php

namespace App\Controller;

use App\Form\FileUploadType;
use App\Service\FileParser;
use App\Service\FileUploader;
use App\Service\PlayerWinsCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    private FileParser $fileParser;

    private PlayerWinsCalculator $playerWinsCalculator;

    /**
     * DefaultController constructor.
     *
     * @param FileParser           $fileParser
     * @param PlayerWinsCalculator $playerWinsCalculator
     */
    public function __construct(FileParser $fileParser, PlayerWinsCalculator $playerWinsCalculator)
    {
        $this->fileParser = $fileParser;
        $this->playerWinsCalculator = $playerWinsCalculator;
    }

    /**
     * @Route("/", name="app_file_upload")
     * @param Request                $request
     * @param FileUploader           $file_uploader
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function indexAction(Request $request, FileUploader $file_uploader, EntityManagerInterface $entityManager
    ) {
        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['upload_file']->getData();
            if ($file) {
                $file_name = $file_uploader->upload($file);
                if (null !== $file_name) // for example
                {
                    $directory = $file_uploader->getTargetDirectory();
                    $fullPath  = $directory.'/'.$file_name;
                    $game      = $this->fileParser->parse($fullPath);
                    $game->setGameFilename($fullPath);
                    $entityManager->persist($game);

                    return $this->render('result.html.twig', [
                        'player1WinsCount' => $this->playerWinsCalculator->calculatePlayer1Wins($game->getRounds())
                    ]);
                } else {
                    $this->addFlash('error', 'The file could\'nt be uploaded!');
                }
            }
        }

        return $this->render(
            'upload.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
