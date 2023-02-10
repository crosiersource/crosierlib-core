<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Config;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"app","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"app"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={"path"="/cfg/app/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "put"={"path"="/cfg/app/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"path"="/cfg/app/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={"path"="/cfg/app", "security"="is_granted('ROLE_ADMIN')"},
 *          "post"={"path"="/cfg/app", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 *
 * @ApiFilter(OrderFilter::class, properties={"id", "UUID", "nome", "updated"}, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibCoreBundle\EntityHandler\Config\AppEntityHandler")
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Config\AppRepository")
 * @ORM\Table(name="cfg_app")
 *
 * @author Carlos Eduardo Pauluk
 */
class App implements EntityId
{

    use EntityIdTrait;


    /**
     * @NotUppercase()
     *
     * @var null|string
     */
    #[ORM\Column(name: 'uuid', type: 'string', nullable: false, length: 36)]
    #[Groups('app')]
    public ?string $UUID = null;

    /**
     *
     * @NotUppercase()
     *
     * @var string|null
     */
    #[ORM\Column(name: 'nome', type: 'string', nullable: true, length: 300)]
    #[Groups('app')]
    public ?string $nome = null;

    /**
     *
     *
     * @var string|null
     */
    #[ORM\Column(name: 'obs', type: 'string', nullable: true, length: 5000)]
    #[Groups('app')]
    public ?string $obs = null;

    /**
     * Transient.
     *
     * @var array|null
     */
    public $configs;


    public function __construct()
    {
        $this->configs = new ArrayCollection();
    }


}
