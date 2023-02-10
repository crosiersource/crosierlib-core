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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"entMenu","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"entMenu"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={"path"="/cfg/entMenu/{id}"},
 *          "put"={"path"="/cfg/entMenu/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"path"="/cfg/entMenu/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={"path"="/cfg/entMenu"},
 *          "post"={"path"="/cfg/entMenu", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 * @ApiFilter(PropertyFilter::class)
 *
 * @ApiFilter(SearchFilter::class, properties={"UUID": "exact", "label": "partial", "id": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"id", "label", "updated", "ordem"}, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibRadxBundle\EntityHandler\Config\EntMenuEntityHandler")
 *
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Config\EntMenuRepository")
 * @ORM\Table(name="cfg_entmenu")
 * @author Carlos Eduardo Pauluk
 */
class EntMenu implements EntityId
{

    use EntityIdTrait;

    /**
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'uuid', type: 'string', nullable: false, length: 36)]
    #[Groups('entity')]
    public ?string $UUID = null;

    /**
     * Necessário para poder montar a URL corretamente (pois o domínio do App pode variar por ambiente).
     *
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'app_uuid', type: 'string', nullable: false, length: 36)]
    #[Groups('entity')]
    public ?string $appUUID = null;

    /**
     *
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'label', type: 'string', nullable: false, length: 255)]
    #[Groups('entity')]
    public ?string $label = null;

    /**
     *
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'icon', type: 'string', nullable: true, length: 50)]
    #[Groups('entity')]
    public ?string $icon = null;

    /**
     *
     *
     * @var string|null
     */
    #[ORM\Column(name: 'tipo', type: 'string', nullable: false, length: 50)]
    #[Groups('entity')]
    public ?string $tipo = null;

    /**
     *
     *
     * @var int|null
     */
    #[ORM\Column(name: 'ordem', type: 'integer', nullable: true)]
    #[Groups('entity')]
    public ?int $ordem = null;

    /**
     *
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'css_style', type: 'string', nullable: true, length: 200)]
    #[Groups('entity')]
    public ?string $cssStyle = null;

    /**
     *
     *
     * @var string|null
     */
    #[ORM\Column(name: 'roles', type: 'string', nullable: true, length: 200)]
    #[Groups('entity')]
    public ?string $roles = null;

    /**
     *
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'url', type: 'string', nullable: false, length: 2000)]
    #[Groups('entity')]
    public ?string $url = null;

    /**
     * @var string
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'pai_uuid', type: 'string', nullable: true, length: 36)]
    #[Groups('entity')]
    public ?string $paiUUID = null;

    /**
     * TRANSIENT
     * @var EntMenu|null
     */
    public ?EntMenu $pai = null;

    /**
     * TRANSIENT
     * @var EntMenu|null
     */
    public ?EntMenu $superPai = null;

    /**
     * TRANSIENT
     * @var null|EntMenu[]|ArrayCollection
     */
    public $filhos;

    /**
     * TRANSIENT.
     * @var int
     */
    public $nivel;

    /**
     * TRANSIENT.
     * @var string
     */
    public string $yaml;


    public function __construct()
    {
        $this->filhos = new ArrayCollection();
    }


}

