<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Base;

use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Base\MunicipioRepository")
 * @ORM\Table(name="bse_municipio")
 * @author Carlos Eduardo Pauluk
 */
class Municipio implements EntityId
{

    use EntityIdTrait;

    /**
     * @ORM\Column(name="municipio_codigo", type="integer", nullable=false)
     * @Groups("entity")
     * @var null|int
     */
    public ?int $municipioCodigo = null;


    /**
     * @ORM\Column(name="municipio_nome", type="string", nullable=true, length=200)
     * @Groups("entity")
     * @var null|string
     */
    public ?string $municipioNome = null;


    /**
     * @ORM\Column(name="uf_nome", type="string", nullable=true, length=200)
     * @Groups("entity")
     * @var null|string
     */
    public ?string $ufNome = null;


    /**
     * @ORM\Column(name="uf_sigla", type="string", nullable=true, length=2)
     * @Groups("entity")
     * @var null|string
     */
    public ?string $ufSigla = null;

    
}