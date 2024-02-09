<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $salary = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $technologies = [];

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(length: 255)]
    private ?string $Company = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'offers')]
    private Collection $AddedBy;

    #[ORM\Column(length: 255)]
    private ?string $experience = null;

    #[ORM\Column(length: 255)]
    private ?string $company_website = null;

    #[ORM\Column(length: 255)]
    private ?string $job_type = null;

    public function __construct()
    {
        $this->AddedBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    public function getTechnologies(): array
    {
        return $this->technologies;
    }

    public function setTechnologies(array $technologies): static
    {
        $this->technologies = $technologies;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->Company;
    }

    public function setCompany(string $Company): static
    {
        $this->Company = $Company;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAddedBy(): Collection
    {
        return $this->AddedBy;
    }

    public function addAddedBy(User $addedBy): static
    {
        if (!$this->AddedBy->contains($addedBy)) {
            $this->AddedBy->add($addedBy);
        }

        return $this;
    }

    public function removeAddedBy(User $addedBy): static
    {
        $this->AddedBy->removeElement($addedBy);

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getCompanyWebsite(): ?string
    {
        return $this->company_website;
    }

    public function setCompanyWebsite(string $company_website): static
    {
        $this->company_website = $company_website;

        return $this;
    }

    public function getJobType(): ?string
    {
        return $this->job_type;
    }

    public function setJobType(string $job_type): static
    {
        $this->job_type = $job_type;

        return $this;
    }
}
