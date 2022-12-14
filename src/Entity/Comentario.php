<?php

namespace App\Entity;


use Symfony\Component\Security\Core\Security;

use App\Repository\ComentarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComentarioRepository::class)
 */
class Comentario
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Mensaje;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ratings;

    /**
     * @ORM\ManyToOne(targetEntity=Evento::class, inversedBy="comentarios")
     */
    private $evento;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    public function __construct()
    {

        $this->createdAt = new \DateTime();
        $this->eventos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMensaje(): ?string
    {
        return $this->Mensaje;
    }

    public function setMensaje(?string $Mensaje): self
    {
        $this->Mensaje = $Mensaje;

        return $this;
    }

    public function getRatings(): ?int
    {
        return $this->ratings;
    }

    public function setRatings(?int $Rating): self
    {
        $this->ratings = $Rating;
        //var_dump($Rating);exit;
        return $this;
    }

    /**
     * @return Collection<int, Evento>
     */
    public function getEventos(): Collection
    {
        return $this->eventos;
    }

    public function addEvento(Evento $evento): self
    {
        if (!$this->eventos->contains($evento)) {
            $this->eventos[] = $evento;
            $evento->addComentario($this);
        }

        return $this;
    }

    public function removeEvento(Evento $evento): self
    {
        if ($this->eventos->removeElement($evento)) {
            $evento->removeComentario($this);
        }

        return $this;
    }

    public function getEvento(): ?Evento
    {
        return $this->evento;
    }

    public function setEvento(?Evento $evento): self
    {
        $this->evento = $evento;

        return $this;
    }

    public function __toString(): string
    {
        return $this->Mensaje;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->format('d/m/Y');
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
