<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Carlos Eduardo Pauluk
 */
trait EntityIdTrait
{

	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: Types::BIGINT)]
	#[Groups("entityId")]
	private ?int $id = null;

	#[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
	#[Groups("entityId")]
	private ?\DateTimeImmutable $inserted = null;

	#[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
	#[Groups("entityId")]
	private ?\DateTime $updated = null;

	#[ORM\Column(type: Types::BIGINT, nullable: false)]
	#[Groups("entityId")]
	private ?string $estabelecimentoId = null;

	#[ORM\Column(type: Types::BIGINT, nullable: false)]
	#[Groups("entityId")]
	private ?string $userInsertedId = null;

	#[ORM\Column(type: Types::BIGINT, nullable: false)]
	#[Groups("entityId")]
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

	public function getInserted(): ?\DateTimeImmutable
	{
		return $this->inserted;
	}

	public function setInserted(?\DateTimeImmutable $inserted): EntityId
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
		return (string)($this->getId() ?? '');
	}


}
