<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Security;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use CrosierSource\CrosierLibCoreBundle\Repository\Security\GroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Entidade 'Group'.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"group","role","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"group"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={"path"="/sec/group/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "put"={"path"="/sec/group/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"path"="/sec/group/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={"path"="/sec/group", "security"="is_granted('ROLE_ADMIN')"},
 *          "post"={"path"="/sec/group", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={"groupname": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"id", "groupname", "updated"}, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibCoreBundle\EntityHandler\Security\GroupEntityHandler")
 *
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Security\GroupRepository")
 * @ORM\Table(name="sec_group")
 *
 * @author Carlos Eduardo Pauluk
 */
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ApiResource(
	operations: [
		new Get(uriTemplate: '/sec/group/{id}', requirements: ['id' => '\d+'], security: "is_granted('ROLE_ADMIN')"),
		new GetCollection(uriTemplate: '/sec/group', security: "is_granted('ROLE_ADMIN')"),
		new Post(uriTemplate: '/sec/group', security: "is_granted('ROLE_ADMIN')"),
		new Put(uriTemplate: '/sec/group/{id}', requirements: ['id' => '\d+'], security: "is_granted('ROLE_ADMIN')"),
		new Delete(uriTemplate: '/sec/group/{id}', requirements: ['id' => '\d+'], security: "is_granted('ROLE_ADMIN')"),
	],
	normalizationContext: ['groups' => ['group', 'role', 'entityId'], 'enable_max_depth' => true],
	denormalizationContext: ['groups' => ['group'], 'enable_max_depth' => true],
)]
class Group implements EntityId
{

	use EntityIdTrait;

	/**
	 *
	 * @ORM\Column(name="groupname", type="string", length=90, unique=true)
	 * @var null|string
	 * @Groups("group")
	 */
	public ?string $groupname = null;

	/**
	 *
	 * @ORM\ManyToMany(targetEntity="Role")
	 * @ORM\JoinTable(name="sec_group_role",
	 *      joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
	 *      )
	 * @Groups("group")
	 */
	public $roles;


	public function __construct()
	{
		$this->roles = new ArrayCollection();
	}


}

