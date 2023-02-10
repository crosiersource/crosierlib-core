<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Security;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Odm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Odm\Filter\SearchFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Entidade 'User'.
 *
 * @ApiResource(
 *     normalizationContext={"groups"={"user","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"user","userPassword","entityId"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={"path"="/sec/user/{id}", "security"="is_granted('ROLE_ADMIN') or object.owner == user"},
 *          "put"={"path"="/sec/user/{id}", "security"="is_granted('ROLE_ADMIN') or object.owner == user"},
 *          "delete"={"path"="/sec/user/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={"path"="/sec/user", "security"="is_granted('ROLE_ADMIN')"},
 *          "post"={"path"="/sec/user", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={
 *     "username": "partial",
 *     "nome": "partial"
 * })
 *
 * @ApiFilter(OrderFilter::class, properties={
 *     "id",
 *     "username",
 *     "nome",
 *     "updated",
 *     "isActive"
 * }, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibCoreBundle\EntityHandler\Security\UserEntityHandler")
 *
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Security\UserRepository")
 * @ORM\Table(name="sec_user")
 * @author Carlos Eduardo Pauluk
 */
class User implements EntityId, UserInterface, \Serializable
{

    use EntityIdTrait;


    /**
     * @NotUppercase()
     * @var null|string
     */
    #[ORM\Column(name: 'username', type: 'string', length: 90, nullable: false)]
    #[Groups('user')]
    public ?string $username = null;


    /**
     * @NotUppercase()
     * @var null|string
     */
    #[ORM\Column(name: 'password', type: 'string', length: 90, nullable: false)]
    #[Groups('userPassword')]
    public ?string $password = null;


    /**
     * @NotUppercase()
     * @var null|string
     */
    #[ORM\Column(name: 'email', type: 'string', length: 90, nullable: false)]
    #[Groups('user')]
    public ?string $email = null;


    /**
     * @var null|string
     */
    #[ORM\Column(name: 'fone', type: 'string', length: 90)]
    #[Groups('user')]
    public ?string $fone = null;


    /**
     *
     * @var null|string
     */
    #[ORM\Column(name: 'nome', type: 'string', length: 90, nullable: false)]
    #[Groups('user')]
    public ?string $nome = null;


    /**
     *
     * @var null|bool
     */
    #[ORM\Column(name: 'ativo', type: 'boolean', nullable: false)]
    #[Groups('user')]
    public bool $isActive = true;


    /**
     *
     *
     * @var null|Group
     */
    #[ORM\ManyToOne(targetEntity: 'CrosierSource\CrosierLibCoreBundle\Entity\Security\Group')]
    #[ORM\JoinColumn(name: 'group_id', nullable: true)]
    #[Groups('user')]
    public ?Group $group = null;


    /**
     * Renomeei o atributo para poder funcionar corretamente com o security do Symfony.
     *
     *
     */
    #[ORM\JoinTable(name: 'sec_user_role')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: 'Role')]
    #[Groups('user')]
    public $userRoles = null;


    /**
     * @NotUppercase()
     * @var null|string
     */
    #[ORM\Column(name: 'api_token', type: 'string', length: 255, nullable: false)]
    public ?string $apiToken = null;


    #[ORM\Column(name: 'api_token_expires_at', type: 'datetime', nullable: false)]
    public ?\DateTime $apiTokenExpiresAt = null;


    /**
     * @NotUppercase()
     * @var null|string
     */
    #[ORM\Column(name: 'token_recupsenha', type: 'string', length: 36, nullable: true)]
    public ?string $tokenRecupSenha = null;


    #[ORM\Column(name: 'dt_valid_token_recupsenha', type: 'datetime', nullable: false)]
    public ?\DateTime $dtValidadeTokenRecupSenha = null;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }


    public function getRoles(): array
    {
        $roles = array();
        foreach ($this->userRoles as $role) {
            $roles[] = $role->getRole();
        }
        return $roles;
    }

    public function addRole(Role $role)
    {
        if (!$this->userRoles->contains($role)) {
            $this->userRoles[] = $role;
        }
        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    public function unserialize($serialized)
    {
        list ($this->id, $this->username, $this->password) = unserialize($serialized, [
            'allowed_classes' => false
        ]);
    }

    public function eraseCredentials()
    {
        // not executed
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getUserIdentifier(): string
    {
        return $this->id . '-' . $this->username;
    }
}
