<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Config;


use CrosierSource\CrosierLibCoreBundle\Business\Syslog\SyslogBusiness;
use CrosierSource\CrosierLibCoreBundle\Entity\Config\Estabelecimento;
use CrosierSource\CrosierLibCoreBundle\Repository\CrosierBaseRepository;
use CrosierSource\CrosierLibCoreBundle\Utils\StringUtils\StringUtils;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @author Carlos Eduardo Pauluk
 */
#[AsDoctrineListener(event: Events::prePersist, priority: 0)]
#[AsDoctrineListener(event: Events::postPersist, priority: 0)]
#[AsDoctrineListener(event: Events::preUpdate, priority: 0)]
#[AsDoctrineListener(event: Events::postUpdate, priority: 0)]
#[AsDoctrineListener(event: Events::preRemove, priority: 0)]
#[AsDoctrineListener(event: Events::postRemove, priority: 0)]
class EstabelecimentoRepository extends CrosierBaseRepository
{

	public function __construct(
		protected ManagerRegistry       $em,
		protected Security              $security,
		protected ParameterBagInterface $parameterBag,
		protected SyslogBusiness        $syslog
	)
	{
		parent::__construct($em, Estabelecimento::class, $this->security, $this->parameterBag, $this->syslog);
	}

	public function beforeSave(/** @var Estabelecimento $estabelecimento */ $estabelecimento): void
	{
		$estabelecimento->codigo = random_int(1000, 9999);
		$estabelecimento->descricao = StringUtils::guidv4();
	}


}
