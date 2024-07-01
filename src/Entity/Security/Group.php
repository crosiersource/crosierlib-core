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
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use CrosierSource\CrosierLibCoreBundle\Repository\Security\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Carlos Eduardo Pauluk
 */
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: 'sec_group')]
#[ApiResource(
	operations: [
		new Get(uriTemplate: '/sec/group/{id}', requirements: ['id' => '\d+'], security: "is_granted('ROLE_ADMIN')"),
		new GetCollection(uriTemplate: '/sec/group', security: "is_granted('ROLE_ADMIN')"),
		new Post(uriTemplate: '/sec/group'),
		new Put(uriTemplate: '/sec/group/{id}', requirements: ['id' => '\d+']),
		new Delete(uriTemplate: '/sec/group/{id}', requirements: ['id' => '\d+']),
	],
	normalizationContext: ['groups' => ['group', 'role', 'entityId'], 'enable_max_depth' => true],
	denormalizationContext: ['groups' => ['group'], 'enable_max_depth' => true],
)]
#[EntityHandler(entityHandlerClass: "CrosierSource\CrosierLibCoreBundle\EntityHandler\Security\GroupEntityHandler")]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'groupname' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'groupname', 'updated'], arguments: ['orderParameterName' => 'order'])]
class Group implements EntityId
{

	use EntityIdTrait;

	#[ORM\Column(name: 'groupname', type: 'string', length: 90, unique: true)]
	#[Groups(['group'])]
	public ?string $groupname = null;


	#[ORM\JoinTable(name: 'sec_group_role')]
	#[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
	#[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id', unique: true)]
	#[ORM\ManyToMany(targetEntity: Role::class)]
	#[Groups(['group'])]
	private Collection $roles;



	public function __construct()
	{
		$this->roles = new ArrayCollection();
	}

	public function setRoles($roles): void
	{
		if (is_array($roles))
			$this->roles = new ArrayCollection($roles);
		else
			$this->roles = $roles;
	}

	public function getRoles(): array
	{
		$roles = array();
		foreach ($this->roles as $role) {
			$roles[] = $role;
		}
		return $roles;
	}


}

