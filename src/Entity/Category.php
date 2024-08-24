<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: CategoryRepository::class)] // Déclare que cette classe est une entité Doctrine avec un dépôt spécifique
#[UniqueEntity('name', message:"Cette catégorie existe déjà.")] // Assure l'unicité du champ 'name' dans la base de données
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column] // Identifie ce champ comme étant la clé primaire de l'entité
    private ?int $id = null;
    
    #[Assert\NotBlank(message: "Le nom de la catégorie est obligatoire")] // Validation : le nom ne peut pas être vide
    #[Assert\Length(
        max: 255,
        maxMessage: 'Le nom ne doit pas dépasser {{ limit }} caractères.', // Validation : le nom ne peut pas dépasser 255 caractères
    )]
    #[ORM\Column(length: 255, unique: true)] // Déclare le champ 'name' comme une colonne de la base de données avec une contrainte d'unicité
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])] // Génère un slug basé sur le nom pour l'URL
    #[ORM\Column(length: 255, unique: true)] // Déclare le champ 'slug' comme une colonne de la base de données avec une contrainte d'unicité
    private ?string $slug = null;

    #[Gedmo\Timestampable(on: 'create')] // Définit ce champ pour être automatiquement mis à jour lors de la création de l'entité
    #[ORM\Column(nullable: true)] // La colonne est nullable dans la base de données
    private ?\DateTimeImmutable $createdAt = null;

    #[Gedmo\Timestampable(on: 'update')] // Définit ce champ pour être automatiquement mis à jour lors de la modification de l'entité
    #[ORM\Column(nullable: true)] // La colonne est nullable dans la base de données
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Product> // Déclaration du type de collection pour les produits associés
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'category', orphanRemoval: true)] // Déclaration de la relation OneToMany avec l'entité Product
    private Collection $products;

    public function __construct()
    {
        // Initialisation de la collection de produits
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        // Retourne l'identifiant de la catégorie
        return $this->id;
    }

    public function getName(): ?string
    {
        // Retourne le nom de la catégorie
        return $this->name;
    }

    public function setName(string $name): static
    {
        // Définit le nom de la catégorie
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        // Retourne le slug de la catégorie
        return $this->slug;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        // Retourne la date de création de la catégorie
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        // Retourne la date de la dernière mise à jour de la catégorie
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        // Retourne la collection des produits associés à la catégorie
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        // Ajoute un produit à la catégorie
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        // Supprime un produit de la catégorie
        if ($this->products->removeElement($product)) {
            // Met à jour le côté propriétaire pour qu'il soit null (si ce n'est pas déjà changé)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }
}
