<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Security;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Entidade 'Role'.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"role","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"role"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={"path"="/sec/role/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "put"={"path"="/sec/role/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"path"="/sec/role/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={"path"="/sec/role", "security"="is_granted('ROLE_ADMIN')"},
 *          "post"={"path"="/sec/role", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={"role": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"id", "role", "updated"}, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibCoreBundle\EntityHandler\Security\RoleEntityHandler")
 *
 * @ORM\Entity(repositoryClass="\CrosierSource\CrosierLibCoreBundle\Repository\Security\RoleRepository")
 * @ORM\Table(name="sec_role")
 *
 * @author Carlos Eduardo Pauluk
 */
class Role implements EntityId
{

    use EntityIdTrait;

    /**
     *
     * @var null|string
     */
    #[ORM\Column(name: 'role', type: 'string', length: 90, unique: true)]
    #[Groups('role')]
    public ?string $role = null;

    /**
     *
     * @var null|string
     */
    #[ORM\Column(name: 'descricao', type: 'string', length: 90)]
    #[Groups('role')]
    public ?string $descricao = null;
    

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }


}

