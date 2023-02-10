<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Config;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Entidade 'Estabelecimento'.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"estabelecimento","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"estabelecimento"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={"path"="/cfg/estabelecimento/{id}", "security"="is_granted('IS_AUTHENTICATED_REMEMBERED')"},
 *          "put"={"path"="/cfg/estabelecimento/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"path"="/cfg/estabelecimento/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={"path"="/cfg/estabelecimento", "security"="is_granted('IS_AUTHENTICATED_REMEMBERED')"},
 *          "post"={"path"="/cfg/estabelecimento", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 * @ApiFilter(PropertyFilter::class)
 *
 * @ApiFilter(SearchFilter::class, properties={"codigo": "exact", "descricao": "partial", "id": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"id", "codigo", "descricao", "updated"}, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibCoreBundle\EntityHandler\Config\EstabelecimentoEntityHandler")
 *
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Config\EstabelecimentoRepository")
 * @ORM\Table(name="cfg_estabelecimento")
 */
class Estabelecimento implements EntityId
{

    use EntityIdTrait;

    /**
     *
     * @var null|int
     */
    #[ORM\Column(name: 'codigo', type: 'integer', nullable: false)]
    #[Groups('estabelecimento')]
    public ?int $codigo = null;

    /**
     *
     * @var null|string
     */
    #[ORM\Column(name: 'descricao', type: 'string', nullable: true, length: 40)]
    #[Groups('estabelecimento')]
    public ?string $descricao = null;

    /**
     *
     * @var null|bool
     */
    #[ORM\Column(name: 'concreto', type: 'boolean', nullable: false)]
    #[Groups('estabelecimento')]
    public ?bool $concreto = false;

    /**
     * @NotUppercase
     */
    #[ORM\Column(name: 'json_data', type: 'json')]
    #[Groups('estabelecimento_jsonData')]
    public ?array $jsonData = null;


}

