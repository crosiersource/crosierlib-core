<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository\Security;

use CrosierSource\CrosierLibCoreBundle\Business\Syslog\SyslogBusiness;
use CrosierSource\CrosierLibCoreBundle\Entity\Security\User;
use CrosierSource\CrosierLibCoreBundle\Exception\ViewException;
use CrosierSource\CrosierLibCoreBundle\Repository\CrosierBaseRepository;
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
class UserRepository extends CrosierBaseRepository
{

	public function __construct(
		protected ManagerRegistry       $em,
		protected Security              $security,
		protected ParameterBagInterface $parameterBag,
		protected SyslogBusiness        $syslog
	)
	{
		parent::__construct($em, User::class, $this->security, $this->parameterBag, $this->syslog);
	}

	/**
	 * @throws ViewException
	 */
	public function getUsersByEmail(string $email): array
	{
		return $this->findAllByFiltersSimpl([['email', 'LIKE', '%' . $email . '%']], ['username' => 'ASC']);
	}

}
