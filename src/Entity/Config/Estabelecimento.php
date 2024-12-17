<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Config;

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
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: "CrosierSource\CrosierLibCoreBundle\Repository\Config\EstabelecimentoRepository")]
#[ORM\Table(name: 'cfg_estabelecimento')]
#[ApiResource(
	operations: [
		new Get(uriTemplate: '/cfg/estabelecimento/{id}', requirements: ['id' => '\d+'], security: "is_granted('ROLE_ADMIN')"),
		new GetCollection(uriTemplate: '/cfg/estabelecimento'),
		new Post(uriTemplate: '/cfg/estabelecimento'),
		new Put(uriTemplate: '/cfg/estabelecimento/{id}', requirements: ['id' => '\d+']),
		new Delete(uriTemplate: '/cfg/estabelecimento/{id}', requirements: ['id' => '\d+']),
	],
	normalizationContext: ['groups' => ['estabelecimento', 'role', 'entityId'], 'enable_max_depth' => true],
	denormalizationContext: ['groups' => ['estabelecimento'], 'enable_max_depth' => true],
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: ['codigo' => 'exact', 'descricao' => 'partial', 'id' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'codigo', 'descricao', 'updated'], arguments: ['orderParameterName' => 'order'])]
#[EntityHandler(entityHandlerClass: "CrosierSource\CrosierLibCoreBundle\EntityHandler\Config\EstabelecimentoEntityHandler")]
class Estabelecimento implements EntityId
{
	use EntityIdTrait;

	#[ORM\Column(name: 'codigo', type: 'integer', nullable: false)]
	#[Groups('estabelecimento')]
	public ?int $codigo = null;

	#[ORM\Column(name: 'descricao', type: 'string', nullable: true, length: 40)]
	#[Groups('estabelecimento')]
	public ?string $descricao = null;

	#[ORM\Column(name: 'concreto', type: 'boolean', nullable: false)]
	#[Groups('estabelecimento')]
	public ?bool $concreto = false;

	#[ORM\Column(name: 'json_data', type: 'json')]
	#[Groups('estabelecimento_jsonData')]
	public ?array $jsonData = null;
}
