<?php

namespace App\Controller;

use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    private $em;
    private $offerRepository;
    public function __construct(EntityManagerInterface $em, OfferRepository $offerRepository)
    {
        $this->em = $em;
        $this->offerRepository = $offerRepository;
    }
    #[Route('/api/offers', name: 'app_offer')]
    public function index(): JsonResponse
    {
        $offers = $this->offerRepository->findAll();

        $offersArray = array_map(function ($offer) {
            return [
                "title" => $offer->getTitle(),
                "description" => $offer->getDescription(),
                "salary" => $offer->getSalary(),
                "technologies" => $offer->getTechnologies(),
                "location" => $offer->getLocation()
            ];
        }, $offers);

        return $this->json([
            "status" => true,
            "offers" => $offersArray
        ]);
    }
}
