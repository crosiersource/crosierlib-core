<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Config;

use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;

#[ORM\Entity(repositoryClass: "CrosierSource\CrosierLibCoreBundle\Repository\Config\MenuItemRepository")]
#[ORM\Table(name: 'cfg_menu_item')]
#[ApiResource(
	operations: [
		new Get(uriTemplate: '/cfg/menuItem/{id}', requirements: ['id' => '\d+']),
		new GetCollection(uriTemplate: '/cfg/menuItem'),
		new Post(uriTemplate: '/cfg/menuItem'),
		new Put(uriTemplate: '/cfg/menuItem/{id}', requirements: ['id' => '\d+']),
		new Delete(uriTemplate: '/cfg/menuItem/{id}', requirements: ['id' => '\d+']),
	],
	normalizationContext: ['groups' => ['menuItem', 'entityId'], 'enable_max_depth' => true],
	denormalizationContext: ['groups' => ['menuItem'], 'enable_max_depth' => true],
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(
	SearchFilter::class,
	properties: [
		'id' => 'exact',
		'label' => 'exact'
	])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'ordem', 'updated', 'items.ordem'], arguments: ['orderParameterName' => 'order'])]
#[ApiFilter(ExistsFilter::class, properties: ['pai'])]
class MenuItem implements EntityId
{
	use EntityIdTrait;

	#[ORM\Column(name: 'label', type: 'string', length: 40, nullable: true)]
	#[Groups('menuItem')]
	#[NotUppercase]
	public ?string $label = null;

	#[ORM\Column(name: 'crosier_app', type: 'string', length: 255, nullable: true)]
	#[Groups('menuItem')]
	#[NotUppercase]
	public ?string $crosierApp = null;

	#[ORM\Column(name: 'url', type: 'string', length: 500, nullable: true)]
	#[Groups('menuItem')]
	#[NotUppercase]
	public ?string $url = null;

	#[ORM\Column(name: 'icon', type: 'string', length: 40, nullable: true)]
	#[Groups('menuItem')]
	#[NotUppercase]
	public ?string $icon = null;

	#[ORM\Column(name: 'tipo', type: 'string', length: 40, nullable: true)]
	#[Groups('menuItem')]
	public ?string $tipo = null;

	#[ORM\Column(name: 'ordem', type: 'integer', nullable: false)]
	#[Groups('menuItem')]
	public ?int $ordem;

	#[ORM\Column(name: 'css_style', type: 'string', length: 2000, nullable: true)]
	#[Groups('menuItem')]
	#[NotUppercase]
	public ?string $cssStyle = null;

	#[ORM\Column(name: 'roles', type: 'string', length: 500, nullable: true)]
	#[Groups('menuItem')]
	public ?string $roles = null;

	#[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'items')]
	#[ORM\JoinColumn(onDelete: 'CASCADE')]
	#[Groups('menuItem')]
	public ?self $pai = null;

	#[ORM\OneToMany(targetEntity: self::class, mappedBy: 'pai', cascade: ['persist', 'remove'])]
	#[Groups('menuItem')]
	#[MaxDepth(1)]
	public Collection $items;

	public function __construct()
	{
		$this->items = new ArrayCollection();
	}

}
