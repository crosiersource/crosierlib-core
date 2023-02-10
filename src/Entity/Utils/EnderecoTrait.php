<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Utils;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Campos padrão para endereços (no Brasil).
 *
 * @package CrosierSource\CrosierLibCoreBundle\Entity\Utils
 * @author Carlos Eduardo Pauluk
 */
trait EnderecoTrait
{

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'cep', type: 'string', nullable: true, length: 9)]
    #[Groups('entity')]
    private $cep;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'logradouro', type: 'string', nullable: true, length: 200)]
    #[Groups('entity')]
    private $logradouro;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'numero', type: 'string', nullable: true, length: 200)]
    #[Groups('entity')]
    private $numero;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'complemento', type: 'string', nullable: true, length: 120)]
    #[Groups('entity')]
    private $complemento;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'bairro', type: 'string', nullable: true, length: 120)]
    #[Groups('entity')]
    private $bairro;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'cidade', type: 'string', nullable: true, length: 120)]
    #[Groups('entity')]
    private $cidade;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'estado', type: 'string', nullable: true, length: 2)]
    #[Groups('entity')]
    private $estado;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'tipo_endereco', type: 'string', nullable: true, length: 100)]
    #[Groups('entity')]
    private $tipoEndereco;

    /**
     *
     * @var string|null
     *
     */
    #[ORM\Column(name: 'obs', type: 'string', nullable: true, length: 3000)]
    #[Groups('entity')]
    private $obs;

    /**
     * @return null|string
     */
    public function getCep(): ?string
    {
        return $this->cep;
    }

    /**
     * @param null|string $cep
     */
    public function setCep(?string $cep): void
    {
        $this->cep = $cep;
    }

    /**
     * @return null|string
     */
    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    /**
     * @param null|string $logradouro
     */
    public function setLogradouro(?string $logradouro): void
    {
        $this->logradouro = $logradouro;
    }

    /**
     * @return null|string
     */
    public function getNumero(): ?string
    {
        return $this->numero;
    }

    /**
     * @param null|string $numero
     */
    public function setNumero(?string $numero): void
    {
        $this->numero = $numero;
    }

    /**
     * @return null|string
     */
    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    /**
     * @param null|string $complemento
     */
    public function setComplemento(?string $complemento): void
    {
        $this->complemento = $complemento;
    }

    /**
     * @return null|string
     */
    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    /**
     * @param null|string $bairro
     */
    public function setBairro(?string $bairro): void
    {
        $this->bairro = $bairro;
    }

    /**
     * @return null|string
     */
    public function getCidade(): ?string
    {
        return $this->cidade;
    }

    /**
     * @param null|string $cidade
     */
    public function setCidade(?string $cidade): void
    {
        $this->cidade = $cidade;
    }

    /**
     * @return null|string
     */
    public function getEstado(): ?string
    {
        return $this->estado;
    }

    /**
     * @param null|string $estado
     */
    public function setEstado(?string $estado): void
    {
        $this->estado = $estado;
    }

    /**
     * @return null|string
     */
    public function getTipoEndereco(): ?string
    {
        return $this->tipoEndereco;
    }

    /**
     * @param null|string $tipoEndereco
     */
    public function setTipoEndereco(?string $tipoEndereco): void
    {
        $this->tipoEndereco = $tipoEndereco;
    }

    /**
     * @return null|string
     */
    public function getObs(): ?string
    {
        return $this->obs;
    }

    /**
     * @param null|string $obs
     */
    public function setObs(?string $obs)
    {
        $this->obs = $obs;
    }


    public function getEnderecoCompleto(): string
    {
        $enderecoCompleto = '';
        $enderecoCompleto .= $this->getLogradouro() . ', ' . $this->getNumero();
        if ($this->getComplemento()) {
            $enderecoCompleto .= ' (' . $this->getComplemento() . ')';
        }
        if ($this->getBairro()) {
            $enderecoCompleto .= ' - ' . $this->getBairro();
        }
        $enderecoCompleto .= ' (CEP: ' . $this->getCep() . ')';
        return $enderecoCompleto;
    }
}