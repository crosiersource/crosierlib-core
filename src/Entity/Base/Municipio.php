<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Base;

use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @author Carlos Eduardo Pauluk
 */
#[ORM\Table(name: 'bse_municipio')]
#[ORM\Entity(repositoryClass: 'CrosierSource\CrosierLibCoreBundle\Repository\Base\MunicipioRepository')]
class Municipio implements EntityId
{

    use EntityIdTrait;

    /**
     * @var null|int
     */
    #[ORM\Column(name: 'municipio_codigo', type: 'integer', nullable: false)]
    #[Groups('entity')]
    public ?int $municipioCodigo = null;


    /**
     * @var null|string
     */
    #[ORM\Column(name: 'municipio_nome', type: 'string', nullable: true, length: 200)]
    #[Groups('entity')]
    public ?string $municipioNome = null;


    /**
     * @var null|string
     */
    #[ORM\Column(name: 'uf_nome', type: 'string', nullable: true, length: 200)]
    #[Groups('entity')]
    public ?string $ufNome = null;


    /**
     * @var null|string
     */
    #[ORM\Column(name: 'uf_sigla', type: 'string', nullable: true, length: 2)]
    #[Groups('entity')]
    public ?string $ufSigla = null;

    
}