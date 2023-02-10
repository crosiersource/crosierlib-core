<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Base;

use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Carlos Eduardo Pauluk
 */
#[ORM\Table(name: 'bse_uf')]
#[ORM\Entity(repositoryClass: 'CrosierSource\CrosierLibCoreBundle\Repository\Base\EstadoRepository')]
class Estado implements EntityId
{

    use EntityIdTrait;

    /**
     * @var null|string
     */
    #[ORM\Column(name: 'nome', type: 'string', nullable: false, length: 50)]
    #[Assert\NotBlank(message: "O campo 'nome' deve ser informado")]
    public ?string $nome = null;

    #[ORM\Column(name: 'sigla', type: 'string', nullable: false, length: 2)]
    #[Assert\NotBlank(message: "O campo 'sigla' deve ser informado")]
    public ?string $sigla = null;

    /**
     * @var null|int
     */
    #[ORM\Column(name: 'codigoIBGE', type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "O campo 'codigoIBGE' deve ser informado")]
    #[Assert\Range(min: 0)]
    public ?int $codigoIBGE = null;

    
}