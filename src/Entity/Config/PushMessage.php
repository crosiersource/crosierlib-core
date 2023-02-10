<?php

namespace CrosierSource\CrosierLibCoreBundle\Entity\Config;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"entity","entityId"},"enable_max_depth"=true},
 *     denormalizationContext={"groups"={"entity"},"enable_max_depth"=true},
 *
 *     itemOperations={
 *          "get"={"path"="/core/config/pushMessage/{id}"},
 *          "put"={"path"="/core/config/pushMessage/{id}", "security"="is_granted('ROLE_ADMIN')"},
 *          "delete"={"path"="/core/config/pushMessage/{id}", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     collectionOperations={
 *          "get"={"path"="/core/config/pushMessage"},
 *          "post"={"path"="/core/config/pushMessage", "security"="is_granted('ROLE_ADMIN')"}
 *     },
 *
 *     attributes={
 *          "pagination_items_per_page"=10,
 *          "formats"={"jsonld", "csv"={"text/csv"}}
 *     }
 * )
 *
 * @ApiFilter(SearchFilter::class, properties={"userDestinatarioId": "exact"})
 * @ApiFilter(OrderFilter::class, properties={"id", "updated"}, arguments={"orderParameterName"="order"})
 *
 * @EntityHandler(entityHandlerClass="CrosierSource\CrosierLibCoreBundle\EntityHandler\Config\PushMessageEntityHandler")
 * @ORM\Entity(repositoryClass="CrosierSource\CrosierLibCoreBundle\Repository\Config\PushMessageRepository")
 * @ORM\Table(name="cfg_pushmessage")
 *
 * @author Carlos Eduardo Pauluk
 */
class PushMessage implements EntityId
{

    use EntityIdTrait;


    /**
     *
     * @NotUppercase()
     *
     * @var null|string
     */
    #[ORM\Column(name: 'mensagem', type: 'string', nullable: false, length: 200)]
    #[Groups('entity')]
    public ?string $mensagem = null;


    /**
     *
     * @NotUppercase()
     *
     * @var null|string
     */
    #[ORM\Column(name: 'url', type: 'string', nullable: true, length: 2000)]
    #[Groups('entity')]
    public ?string $url = null;


    /**
     *
     * @var null|integer
     */
    #[ORM\Column(name: 'user_destinatario_id', type: 'bigint', nullable: false)]
    #[Groups('entity')]
    public ?int $userDestinatarioId = null;


    /**
     * Data em que a mensagem foi enviada.
     *
     *
     * @var null|\DateTime
     */
    #[ORM\Column(name: 'dt_envio', type: 'datetime', nullable: false)]
    #[Groups('entity')]
    public ?\DateTime $dtEnvio = null;


    /**
     * Data em que a mensagem foi exibida na notificação.
     *
     *
     * @var null|\DateTime
     */
    #[ORM\Column(name: 'dt_notif', type: 'datetime', nullable: true)]
    #[Groups('entity')]
    public ?\DateTime $dtNotif = null;


    /**
     * Data em que a mensagem foi aberta na tela de mensagens.
     *
     *
     * @var null|\DateTime
     */
    #[ORM\Column(name: 'dt_abert', type: 'datetime', nullable: true)]
    #[Groups('entity')]
    public ?\DateTime $dtAbert = null;


    /**
     * Data de validade da mensagem (após essa data, ela não é mais notificada).
     *
     *
     * @var null|\DateTime
     */
    #[ORM\Column(name: 'dt_validade', type: 'datetime', nullable: true)]
    #[Groups('entity')]
    public ?\DateTime $dtValidade = null;

    /**
     *
     *
     * @var null|string
     */
    #[ORM\Column(name: 'params', type: 'string', nullable: true, length: 5000)]
    #[Groups('entity')]
    public ?string $params = null;


}