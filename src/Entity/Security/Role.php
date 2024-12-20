<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Security;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use CrosierSource\CrosierLibCoreBundle\Repository\Security\RoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
#[ORM\Table(name: 'sec_role')]
#[ApiResource(
	operations: [
		new Get(uriTemplate: '/sec/role/{id}', requirements: ['id' => '\d+']),
		new GetCollection(uriTemplate: '/sec/role'),
		new Post(uriTemplate: '/sec/role'),
		new Put(uriTemplate: '/sec/role/{id}', requirements: ['id' => '\d+']),
		new Delete(uriTemplate: '/sec/role/{id}', requirements: ['id' => '\d+']),
	],
	normalizationContext: ['groups' => ['role', 'entityId'], 'enable_max_depth' => true],
	denormalizationContext: ['groups' => ['role'], 'enable_max_depth' => true],
)]
#[ApiFilter(SearchFilter::class, properties: ['role' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'role', 'updated'], arguments: ['orderParameterName' => 'order'])]
class Role implements EntityId
{
	use EntityIdTrait;

	#[ORM\Column(name: 'role', type: 'string', length: 90, unique: true)]
	#[Groups('role')]
	public ?string $role = null;

	#[ORM\Column(name: 'descricao', type: 'string', length: 90)]
	#[Groups('role')]
	public ?string $descricao = null;

	public function getRole(): ?string
	{
		return $this->role;
	}

	public function setRole(?string $role): void
	{
		$this->role = $role;
	}


}
