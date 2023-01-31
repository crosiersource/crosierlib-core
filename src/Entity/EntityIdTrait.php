<?php /** @noinspection PhpIncompatibleReturnTypeInspection */

namespace CrosierSource\CrosierLibCoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Carlos Eduardo Pauluk
 */
trait EntityIdTrait
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups('entityId')]
    private ?int $id = null;


    #[ORM\Column(name: 'inserted', type: 'datetime', nullable: false)]
    #[Assert\Type('\DateTime')]
    #[Groups('entityId')]
    private ?\DateTime $inserted = null;


    #[ORM\Column(name: 'updated', type: 'datetime', nullable: false)]
    #[Assert\Type('\DateTime')]
    #[Groups('entityId')]
    private ?\DateTime $updated = null;


    #[ORM\Column(name: 'estabelecimento_id', type: 'bigint', nullable: false)]
    #[Groups('entityId')]
    private ?string $estabelecimentoId = null;


    #[ORM\Column(name: 'user_inserted_id', type: 'bigint', nullable: false)]
    #[Groups('entityId')]
    private ?string $userInsertedId = null;


    #[ORM\Column(name: 'user_updated_id', type: 'bigint', nullable: false)]
    #[Groups('entityId')]
    private ?string $userUpdatedId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): EntityId
    {
        $this->id = $id;
        return $this;
    }

    public function getInserted(): ?\DateTime
    {
        return $this->inserted;
    }

    /**
     * @param mixed $inserted
     * @return EntityId
     */
    public function setInserted(?\DateTime $inserted): EntityId
    {
        $this->inserted = $inserted;
        return $this;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTime $updated): EntityId
    {
        $this->updated = $updated;
        return $this;
    }

    public function getEstabelecimentoId(): ?string
    {
        return $this->estabelecimentoId;
    }

    public function setEstabelecimentoId(?string $estabelecimentoId): EntityId
    {
        $this->estabelecimentoId = $estabelecimentoId;
        return $this;
    }

    public function getUserInsertedId(): ?string
    {
        return (string)$this->userInsertedId;
    }

    public function setUserInsertedId($userInsertedId): EntityId
    {
        $this->userInsertedId = $userInsertedId;
        return $this;
    }

    public function getUserUpdatedId(): ?string
    {
        return $this->userUpdatedId;
    }

    public function setUserUpdatedId(?string $userUpdatedId): EntityId
    {
        $this->userUpdatedId = $userUpdatedId;
        return $this;
    }

    public function __toString(): string
    {
        return '' . ($this->getId() ?? '');
    }


}
