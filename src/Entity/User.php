<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="UNIQ_8D93D649E7927C74", columns={"email"})}, indexes={@ORM\Index(name="username", columns={"username"})})
 * @ORM\Entity
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=180, nullable=false)
     */
    private $email;
    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json", nullable=false)
     */
    private $roles= [];
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * 
     */
    private $username;

    /**
     * @ORM\ManyToMany(targetEntity=Project::class, mappedBy="membersOfTheProject")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity=Project::class, mappedBy="managersOfTheProject")
     */
    private $managers;

    /**
     * @ORM\ManyToMany(targetEntity=Evento::class, inversedBy="users")
     */
    private $favorites;

    /**
     * @ORM\ManyToMany(targetEntity=RestaurantesBares::class, mappedBy="usuarios")
     */
    private $restaurantesBares;

    /**
     * @ORM\OneToMany(targetEntity=Comentario::class, mappedBy="user")
     */
    private $comments;



    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->managers = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->restaurantesBares = new ArrayCollection();
        $this->comments = new ArrayCollection();
    
     
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
       
        $roles[] = 'ROLE_ANONYMOUS';
      
        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function __toString() {
        return $this->username;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (!$this->projects->contains($project)) {
            $this->projects[] = $project;
            $project->addMembersOfTheProject($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        if ($this->projects->removeElement($project)) {
            $project->removeMembersOfTheProject($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(Project $manager): self
    {
        if (!$this->managers->contains($manager)) {
            $this->managers[] = $manager;
            $manager->setManagersOfTheProject($this);
        }

        return $this;
    }

    public function removeManager(Project $manager): self
    {
        if ($this->managers->removeElement($manager)) {
            // set the owning side to null (unless already changed)
            if ($manager->getManagersOfTheProject() === $this) {
                $manager->setManagersOfTheProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evento>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Evento $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
        }

        return $this;
    }

    public function removeFavorite(Evento $favorite): self
    {
        $this->favorites->removeElement($favorite);

        return $this;
    }

    /**
     * @return Collection<int, RestaurantesBares>
     */
    public function getRestaurantesBares(): Collection
    {
        return $this->restaurantesBares;
    }

    public function addRestaurantesBare(RestaurantesBares $restaurantesBare): self
    {
        if (!$this->restaurantesBares->contains($restaurantesBare)) {
            $this->restaurantesBares[] = $restaurantesBare;
            $restaurantesBare->addUsuario($this);
        }

        return $this;
    }

    public function removeRestaurantesBare(RestaurantesBares $restaurantesBare): self
    {
        if ($this->restaurantesBares->removeElement($restaurantesBare)) {
            $restaurantesBare->removeUsuario($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comentario $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comentario $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }






}