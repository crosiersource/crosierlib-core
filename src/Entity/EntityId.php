<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity;

/**
 * @author Carlos Eduardo Pauluk
 */
interface EntityId
{

	public function getId(): ?int;

	public function setId(?int $id): EntityId;

	public function getInserted(): ?\DateTimeImmutable;

	public function setInserted(?\DateTimeImmutable $inserted): EntityId;

	public function getUpdated(): ?\DateTime;

	public function setUpdated(\DateTime $updated): EntityId;

	public function getEstabelecimentoId(): ?string;

	public function setEstabelecimentoId(?string $estabelecimentoId): EntityId;

	public function getUserInsertedId(): ?string;

	public function setUserInsertedId(?string $userInsertedId): EntityId;

	public function getUserUpdatedId(): ?string;

	public function setUserUpdatedId(?string $userUpdatedId): EntityId;


}
