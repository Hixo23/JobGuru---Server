<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Entity\User;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiOfferController extends AbstractController
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
                "id" => $offer->getId(),
                "title" => $offer->getTitle(),
                "description" => $offer->getDescription(),
                "salary" => $offer->getSalary(),
                "technologies" => $offer->getTechnologies(),
                "location" => $offer->getLocation(),
                "company" => $offer->getCompany(),
                "job_type" => $offer->getJobType(),
                "experience" => $offer->GetExperience(),
                "company_website" => $offer->getCompanyWebsite()
            ];
        }, $offers);

        return $this->json([
            "offers" => $offersArray
        ]);
    }

    #[Route('/api/offers/add', name: "add_offer", methods: ["post"])]
    public function addOffer(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        $currentUser = $this->getUser();

        if (empty($body["title"]) || empty($body["description"]) || empty($body["location"]) || empty($body["technologies"]) || empty($body["salary"]) || empty($body["company"]) || empty($body["experience"]) || empty($body["company_website"]) || empty($body["job_type"])) {
            return $this->json([
                "message" => "Invalid data in the request body"
            ], 422);
        }

        $offer = new Offer();
        $offer->setTitle($body["title"]);
        $offer->setDescription($body["description"]);
        $offer->setLocation($body["location"]);
        $offer->setSalary(intval($body["salary"]));
        $offer->setTechnologies($body["technologies"]);
        $offer->setCompany($body["company"]);
        $offer->setExperience($body["experience"]);
        $offer->setCompanyWebsite($body["company_website"]);
        $offer->setJobType($body["job_type"]);
        $offer->addAddedBy($currentUser);

        $this->em->persist($offer);
        $this->em->flush();

        return $this->json([
            "message" => "offer created"
        ]);
    }
    #[Route('/api/offer/{id}', name: "delete_offer", methods: ["delete"])]
    public function deleteOffer(Int $id)
    {
        $offer  = $this->offerRepository->find($id);

        if (empty($offer)) {
            return $this->json([
                "message" => "offer not found"
            ], 404);
        }
        $this->em->remove($offer);
        $this->em->flush();

        return $this->json([
            "message" => "offer deleted"
        ]);
    }
}
