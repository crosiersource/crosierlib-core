<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Security;

use AllowDynamicProperties;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use CrosierSource\CrosierLibCoreBundle\Repository\Security\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'sec_user')]
#[ApiResource(
	operations: [
		new Get(uriTemplate: '/sec/user/{id}', requirements: ['id' => '\d+']),
		new GetCollection(uriTemplate: '/sec/user'),
		new Post(uriTemplate: '/sec/user'),
		new Put(uriTemplate: '/sec/user/{id}', requirements: ['id' => '\d+']),
		new Delete(uriTemplate: '/sec/user/{id}', requirements: ['id' => '\d+']),
	],
	normalizationContext: ['groups' => ['user', 'entityId'], 'enable_max_depth' => true],
	denormalizationContext: ['groups' => ['user', 'userPassword', 'entityId'], 'enable_max_depth' => true],
)]
#[ApiFilter(SearchFilter::class, properties: ['username' => 'partial', 'nome' => 'partial', 'email' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'username', 'nome', 'updated', 'isActive'], arguments: ['orderParameterName' => 'order'])]
#[AllowDynamicProperties]
class User implements EntityId, UserInterface, \Serializable, PasswordAuthenticatedUserInterface
{
	use EntityIdTrait;

	#[ORM\Column(name: 'username', type: 'string', length: 90, nullable: false)]
	#[Groups('user')]
	#[NotUppercase]
	public ?string $username = null;

	#[ORM\Column(name: 'password', type: 'string', length: 90, nullable: false)]
	#[Groups('userPassword')]
	#[NotUppercase]
	public ?string $password = null;

	#[ORM\Column(name: 'email', type: 'string', length: 90, nullable: false)]
	#[Groups('user')]
	#[NotUppercase]
	public ?string $email = null;

	#[ORM\Column(name: 'fone', type: 'string', length: 90)]
	#[Groups('user')]
	public ?string $fone = null;

	#[ORM\Column(name: 'nome', type: 'string', length: 90, nullable: false)]
	#[Groups('user')]
	public ?string $nome = null;

	#[ORM\Column(name: 'descricao', type: 'string', length: 255, nullable: true)]
	#[Groups('user')]
	public ?string $descricao = null;

	#[ORM\Column(name: 'ativo', type: 'boolean', nullable: false)]
	#[Groups('user')]
	public bool $isActive = true;

	#[ORM\ManyToOne(targetEntity: Group::class)]
	#[ORM\JoinColumn(name: 'group_id', nullable: true)]
	#[Groups('user')]
	public ?Group $group = null;

	#[ORM\ManyToMany(targetEntity: Role::class)]
	#[ORM\JoinTable(name: 'sec_user_role')]
	#[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
	#[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id')]
	#[Groups('user')]
	public Collection $userRoles;

	#[ORM\Column(name: 'api_token', type: 'string', length: 255, nullable: false)]
	#[Groups('userPassword')]
	#[NotUppercase]
	public ?string $apiToken = null;

	#[ORM\Column(name: 'api_token_expires_at', type: 'datetime', nullable: false)]
	#[Groups('userPassword')]
	public ?\DateTime $apiTokenExpiresAt = null;

	#[ORM\Column(name: 'token_recupsenha', type: 'string', length: 36, nullable: true)]
	#[Groups('userPassword')]
	#[NotUppercase]
	public ?string $tokenRecupSenha = null;

	#[ORM\Column(name: 'dt_valid_token_recupsenha', type: 'datetime', nullable: false)]
	#[Groups('userPassword')]
	public ?\DateTime $dtValidadeTokenRecupSenha = null;


	public function __construct()
	{
		$this->userRoles = new ArrayCollection();
	}

	public function setUserRoles($userRoles): void
	{
		if (is_array($userRoles))
			$this->userRoles = new ArrayCollection($userRoles);
		else
			$this->userRoles = $userRoles;
	}

	public function getUserRoles(): array
	{
		$userRoles = array();
		foreach ($this->userRoles as $userRole) {
			$userRoles[] = $userRole;
		}
		return $userRoles;
	}

	public function addRole(Role $role): static
	{
		if (!$this->userRoles->contains($role)) {
			$this->userRoles[] = $role;
		}
		return $this;
	}

	public function eraseCredentials(): void
	{
	}

	public function getSalt(): null
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

	public function getDescricaoMontada(): ?string
	{
		$d = $this->username . ' - ' . $this->nome;
		if ($this->descricao) {
			$d .= ' (' . $this->descricao . ')';
		}
		return $d;
	}

	public function getUserIdentifier(): string
	{
		return $this->username;
	}


	public function __serialize(): array
	{
		return [
			$this->id,
			$this->username,
			$this->password
		];
	}

	public function __unserialize($serialized)
	{
		list ($this->id, $this->username, $this->password) = unserialize($serialized, [
			'allowed_classes' => false
		]);
	}


	public function serialize(): ?string
	{
		return serialize([
			$this->id,
			$this->username,
			$this->password
		]);
	}

	public function unserialize(string $data): void
	{
		list ($this->id, $this->username, $this->password) = unserialize($data, [
			'allowed_classes' => false
		]);
	}

	public function getRoles(): array
	{
		return $this->getUserRoles();
	}


}
