<?php

namespace CrosierSource\CrosierLibCoreBundle\Repository;

use CrosierSource\CrosierLibCoreBundle\Business\Syslog\SyslogBusiness;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\Security\User;
use CrosierSource\CrosierLibCoreBundle\Exception\ViewException;
use CrosierSource\CrosierLibCoreBundle\Utils\DateTimeUtils\DateTimeUtils;
use CrosierSource\CrosierLibCoreBundle\Utils\ExceptionUtils\ExceptionUtils;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @author Carlos Eduardo Pauluk
 */
abstract class CrosierBaseRepository extends FilterRepository
{

	protected bool $isTransacionalSave = false;

	protected bool $shouldFlush = true;

	protected bool $isInserting = false;

	protected bool $salvouLogInsert = false;


	public function __construct(
		protected ManagerRegistry       $em,
		string                          $entityClass,
		protected Security              $security,
		protected ParameterBagInterface $parameterBag,
		protected SyslogBusiness        $syslog)
	{
		parent::__construct($em, $entityClass, $this->security, $this->parameterBag, $this->syslog);
	}

	private function validEntity($args): bool
	{
		return $args->getObject() instanceof EntityId;
		//&& $this->getEntityClass() === get_class($args->getObject());
	}

	public function prePersist(PrePersistEventArgs $args): void
	{
		if (!$this->validEntity($args)) return;
		$this->preSave($args->getObject());
	}

	public function postPersist(PostPersistEventArgs $args): void
	{
		if (!$this->validEntity($args)) return;
		$this->postSave($args->getObject());
	}

	public function preUpdate(PreUpdateEventArgs $args): void
	{
		if (!$this->validEntity($args)) return;
		$this->preSave($args->getObject());
	}

	public function postUpdate(PostUpdateEventArgs $args): void
	{
		if (!$this->validEntity($args)) return;
		$this->postSave($args->getObject());
	}

	public function preRemove(PreRemoveEventArgs $args): void
	{
		if (!$this->validEntity($args)) return;
		$this->beforeDelete($args->getObject());
	}

	public function postRemove(PostRemoveEventArgs $args): void
	{
		if (!$this->validEntity($args)) return;
		$this->afterDelete($args->getObject());
	}


	/**
	 * @throws ViewException
	 */
	public function delete($entityId): void
	{
		try {
			if ($this->isTransacionalSave) {
				$this->em->getManager()->beginTransaction();
			}
			$this->beforeDelete($entityId);
			$this->em->getManager()->remove($entityId);
			$this->em->getManager()->flush();
			$this->afterDelete($entityId);
			if ($this->isTransacionalSave) {
				$this->em->getManager()->commit();
			}
		} catch (\Exception $e) {
			if ($this->isTransacionalSave) {
				$this->em->getManager()->rollback();
			}
			$msg = ExceptionUtils::treatException($e);
			throw new ViewException('Erro ao deletar' . ($msg ? ' (' . $msg . ')' : ''), 0, $e, $this->syslog);
		}
	}


	/**
	 * Para ser sobreescrito (caso necessário).
	 */
	public function beforeDelete($entityId): void
	{
	}


	/**
	 * EntityId
	 *
	 * @param $entityId
	 */
	public function afterDelete($entityId): void
	{
	}


	public function cloneEntityId(EntityId $e): EntityId
	{
		$newE = clone $e;
		$this->beforeClone($newE);
		$newE->setId(null);
		$newE->setInserted(null);
		$newE->setUpdated(null);
		$newE->setUserInsertedId(null);
		$newE->setUserUpdatedId(null);
		$newE->setEstabelecimentoId(null);
		return $newE;
	}


	/**
	 * Copia o objeto removendo informações específicas.
	 * @throws ViewException
	 */
	public function doClone(EntityId $e): EntityId
	{
		$this->em->getManager()->beginTransaction();
		$newE = $this->cloneEntityId($e);
		$this->afterClone($newE, $e);
		$this->save($newE);
		$this->em->getManager()->commit();
		return $newE;
	}


	/**
	 * Para ser sobreescrito (caso necessário).
	 */
	public function beforeClone($entityId): void
	{
	}


	/**
	 * Para ser sobreescrito (caso necessário).
	 */
	public function afterClone($newEntityId, $oldEntityId): void
	{
	}


	/**
	 * @throws ViewException
	 */
	public function preSave(EntityId $entityId): EntityId
	{
		try {
			if ($this->isTransacionalSave) {
				$this->em->getManager()->beginTransaction();
			}
			$this->handleSavingEntityId($entityId);
			$this->beforeSave($entityId);
		} catch (\Throwable $e) {
			if ($this->isTransacionalSave) {
				$this->em->getManager()->rollback();
			}
			$msg = ExceptionUtils::treatException($e);
			$msg = $msg ? 'Erro ao salvar (' . $msg . ')' : 'Erro ao salvar';
			throw new ViewException($msg, 0, $e, $this->syslog);
		}
		return $entityId;
	}


	public function postSave(EntityId $entityId): void
	{
//		if ($this->shouldFlush) {
//			$this->em->getManager()->flush();
//		}

		$this->afterSave($entityId);

		$this->handleJsonMetadata();
		if ($this->isTransacionalSave) {
			$this->em->getManager()->commit();
		}
		$this->posAfterSave($entityId);
	}


	public function handleSavingEntityId($entityId): void
	{
		try {
			/** @var EntityId $entityId */
			$this->handleUppercaseFields($entityId);
			if (!$entityId->getId()) {
				$entityId->setInserted(new \DateTimeImmutable('now'));
			}
			$entityId->setUpdated(new \DateTime('now'));
			if ($this->security->getUser()) {
				/** @var User $user */
				$user = $this->security->getUser();
				if (!$entityId->getEstabelecimentoId()) {
					$entityId->setEstabelecimentoId($user->getEstabelecimentoId());
				}
				$entityId->setUserUpdatedId($user->getId());
				if (!$entityId->getId()) {
					$entityId->setUserInsertedId($user->getId());
				}
			} else {
				if (!$entityId->getEstabelecimentoId()) {
					$entityId->setEstabelecimentoId(1);
				}
				if (!$entityId->getUserInsertedId()) {
					$entityId->setUserInsertedId(1);
				}
				if (!$entityId->getUserUpdatedId()) {
					$entityId->setUserUpdatedId(1);
				}
			}
		} catch (\Exception $e) {
			$msg = 'Erro ao handleSavingEntityId (' . ExceptionUtils::treatException($e) . ')';
			$this->syslog->err($msg);
			throw new \RuntimeException($msg);
		}
	}


	protected function handleJsonMetadata(): void
	{
		$tableName = $this->em->getManager()->getClassMetadata($this->entityClass)->getTableName();

		$conn = $this->em->getManager()->getConnection();
		$rConfig = $conn->fetchAllAssociative('SELECT * FROM cfg_app_config WHERE app_uuid = :appUUID AND chave = :chave', ['appUUID' => $_SERVER['CROSIERAPP_UUID'], 'chave' => $tableName . '_json_metadata']);

		if ($rConfig) {
			$cfgAppConfig = $rConfig[0];
			$jsonMetadata = json_decode($cfgAppConfig['valor'], true);
			$mudou = null;
			foreach ($jsonMetadata['campos'] as $campo => $metadata) {
				if ((($metadata['tipo'] ?? '') === 'tags') &&
					(isset($metadata['sugestoes']) or (strpos(($metadata['class'] ?? ''), 's2allownew') !== FALSE))) {
					$valoresNaBase = $conn->fetchAllAssociative('SELECT distinct(json_data->>"$.' . $campo . '") as val FROM ' . $tableName . ' WHERE json_data->>"$.' . $campo . '" NOT IN (\'\',\'null\') ORDER BY json_data->>"$.' . $campo . '"');
					foreach ($valoresNaBase as $v) {
						$valExploded = explode(',', $v['val']);
						foreach ($valExploded as $val) {
							if ($val && !in_array($val, $metadata['sugestoes'])) {
								$metadata['sugestoes'][] = $val;
								$mudou = true;
							}
						}
					}
					if ($mudou) {
						sort($metadata['sugestoes']);
						$jsonMetadata['campos'][$campo]['sugestoes'] = $metadata['sugestoes'];
					}
				}
			}
			if ($mudou) {
				$cfgAppConfig['valor'] = json_encode($jsonMetadata);
				$cfgAppConfig['is_json'] = (bool)$cfgAppConfig['is_json'] ? 1 : 0;
				$conn->update('cfg_app_config', $cfgAppConfig, ['id' => $cfgAppConfig['id']]);
			}
		}
	}


	private function handleUppercaseFields($entityId): void
	{
		try {
			$uppercaseFieldsJson = file_get_contents($this->parameterBag->get('kernel.project_dir') . '/src/Entity/uppercaseFields.json');
			$uppercaseFields = json_decode($uppercaseFieldsJson);
			$class = str_replace('\\', '_', get_class($entityId));
			$reflectionClass = new \ReflectionClass(get_class($entityId));
			$campos = $uppercaseFields->$class ?? [];
			foreach ($campos as $field) {
				$property = $reflectionClass->getProperty($field);
				$property->setAccessible(true);
				$property->setValue($entityId, trim(mb_strtoupper($property->getValue($entityId))));
			}
		} catch (\ReflectionException $e) {
			$msg = ExceptionUtils::treatException($e);
			$msg = 'Erro em handleUppercaseFields (' . $msg . ')';
			$this->syslog->err($msg);
			throw new \RuntimeException($msg, 0, $e);
		}
	}


	/**
	 * Para ser sobreescrito (caso necessário).
	 */
	public function beforeSave($entityId): void
	{

	}


	/**
	 * Para ser sobreescrito (caso necessário).
	 */
	public function afterSave($entityId): void
	{
	}


	/**
	 * Implementação vazia pois não é obrigatório.
	 *
	 * @param $entityId
	 */
	public function posAfterSave($entityId): void
	{
	}


	public function updateUpdated(string $tableName, int $id): void
	{
		if (!$id) {
			throw new ViewException('Impossível realizar updated sem id da entidade');
		}
		$userId = null;
		if ($this->security->getUser()) {
			/** @var User $user */
			$user = $this->security->getUser();
			$userId = $user->getId();
		}
		$params = [
			'updated' => DateTimeUtils::getSQLFormatted(),
		];
		if ($userId) {
			$params['user_updated_id'] = $userId;
		}
		$this->em->getManager()->getConnection()->update($tableName, $params, ['id' => $id]);
	}


	protected function getRegistroDaTabela(EntityId $entityId): ?array
	{
		$tableName = $this->em->getManager()->getClassMetadata($this->getEntityClass())->getTableName();
		$sql = 'SELECT * FROM ' . $tableName . ' WHERE id = :id';
		$params = ['id' => $entityId->getId()];
		return $this->em->getManager()->getConnection()->fetchAssociative($sql, $params);
	}


}
