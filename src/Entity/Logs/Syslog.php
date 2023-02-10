<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Logs;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


/**
 * @ApiResource(
 *     normalizationContext={"groups"={"entity","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"entity"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={
 *              "path"="/core/config/syslog/{id}",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"entity","entityId", "obs"}},
 *             },
 *          "put"={"path"="/core/config/syslog/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"path"="/core/config/syslog/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={
 *              "path"="/core/config/syslog",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"entity","entityId", "obsp"}},
 *              },
 *          "post"={"path"="/core/config/syslog", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 *
 * @ApiFilter(DateFilter::class, properties={"moment"})
 * 
 * @ApiFilter(SearchFilter::class, properties={
 *     "id": "exact",
 *     "app": "exact",
 *     "uuidSess": "exact",
 *     "tipo": "exact",
 *     "component": "partial",
 *     "act": "partial",
 *     "username": "exact",
 *     "obs": "partial"
 * })
 * 
 * @ApiFilter(OrderFilter::class, properties={"id", "app", "component", "moment", "updated"}, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibCoreBundle\EntityHandler\Config\SyslogHandler")
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Config\SyslogRepository")
 * @ORM\Table(name="cfg_syslog")
 *
 * @author Carlos Eduardo Pauluk
 */
class Syslog
{

    /**
     * @var null|int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'bigint')]
    #[Groups('entityId')]
    public ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'uuid_sess', type: 'string')]
    #[Groups('entity')]
    public ?string $uuidSess = null;
    
    /**
     * @var string|null
     */
    #[ORM\Column(name: 'tipo', type: 'string')]
    #[Groups('entity')]
    public ?string $tipo = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'app', type: 'string')]
    #[Groups('entity')]
    public ?string $app = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'component', type: 'string')]
    #[Groups('entity')]
    public ?string $component = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'act', type: 'string')]
    #[Groups('entity')]
    public ?string $act = null;

    /**
     * @NotUppercase()
     * @var string|null
     */
    #[ORM\Column(name: 'username', type: 'string')]
    #[Groups('entity')]
    public ?string $username = null;

    /**
     * @var null|\DateTime
     */
    #[ORM\Column(name: 'moment', type: 'datetime')]
    #[Groups('entity')]
    public ?\DateTime $moment = null;

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'obs', type: 'string')]
    #[Groups('obs')]
    public ?string $obs = null;

    /**
     * @var null|\DateTime
     */
    #[ORM\Column(name: 'delete_after', type: 'datetime')]
    #[Groups('entityId')]
    public ?\DateTime $deleteAfter = null;

    /**
     * @var null|array
     * @NotUppercase()
     */
    #[ORM\Column(name: 'json_data', type: 'json')]
    #[Groups('entity')]
    public ?array $jsonData = null;


    /**
     * @var null|string
     */
    #[Groups('obsp')]
    #[SerializedName('obs')]
    public function getObsp(): ?string
    {
        return $this->obs ? (substr($this->obs, 0, 200) . '...') : null;
    }


}
