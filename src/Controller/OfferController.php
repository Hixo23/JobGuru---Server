<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/api/offers', name: 'app_offer', methods: ["get"])]
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

    #[Route('/api/offers', name: "add_offer", methods: ["post"])]
    public function addOffer(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);

        if (empty($body["title"]) || empty($body["description"]) || empty($body["location"]) || empty($body["technologies"]) || empty($body["salary"])) {
            return $this->json([
                "status" => false,
                "message" => "Invalid data in the request body"
            ], 422);
        }

        $offer = new Offer();
        $offer->setTitle($body["title"]);
        $offer->setDescription($body["description"]);
        $offer->setLocation($body["location"]);
        $offer->setSalary(intval($body["salary"]));
        $offer->setTechnologies($body["technologies"]);

        $this->em->persist($offer);
        $this->em->flush();

        return $this->json([
            "status" => true,
            "message" => "offer created"
        ]);
    }
}
