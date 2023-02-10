<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Security;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
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
class Group implements EntityId
{
    use EntityIdTrait;

    /**
     *
     * @var null|string
     */
    #[ORM\Column(name: 'groupname', type: 'string', length: 90, unique: true)]
    #[Groups('group')]
    public ?string $groupname = null;

    #[ORM\JoinTable(name: 'sec_group_role')]
    #[ORM\JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: 'Role')]
    #[Groups('group')]
    public $roles;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    
}

