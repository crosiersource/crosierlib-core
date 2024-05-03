<?php


namespace CrosierSource\CrosierLibCoreBundle\EntityHandler;


use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Exception\ViewException;

/**
 * Interface EntityHandlerInterface
 *
 * @package CrosierSource\CrosierLibCoreBundle\EntityHandler
 * @author Carlos Eduardo Pauluk
 */
interface EntityHandlerInterface
{
    /**
     * Qual a entidade que este EntityHandler trabalhará.
     *
     * @return mixed
     */
    public function getEntityClass();

    /**
     * Executado no início do método save().
     *
     * @param $entityId
     * @return mixed
     */
    public function beforeSave($entityId);

    /**
     * Executa o persist/update e o flush.
     *
     * @param EntityId $entityId
     * @param bool $flush
     * @return EntityId|object
     * @throws ViewException
     */
    public function save(EntityId $entityId, $flush = true);

    /**
     * Executado após o save().
     *
     * @param $entityId
     */
    public function afterSave($entityId);

    /**
     * Executado no início do método delete().
     *
     * @param $entityId
     */
    public function beforeDelete($entityId);

    /**
     * Executa o DELETE e o flush.
     *
     * @param $entityId
     */
    public function delete($entityId);

    /**
     * Executado após o delete().
     *
     * @param $entityId
     */
    public function afterDelete($entityId);

    /**
     * Executado no início do método clone().
     *
     * @param $entityId
     */
    public function beforeClone($entityId);

    /**
     * Copia o objeto removendo informações específicas.
     *
     * @param $e
     * @return EntityId|object
     */
    public function doClone($e);


}
