<?php

namespace CrosierSource\CrosierLibCoreBundle\StateProcessor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\EntityHandler\TorneioEntityHandler;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\EntityHandler;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

// use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Carlos Eduardo Pauluk
 */
class EntityHandlerStateProcessor implements ProcessorInterface, ServiceSubscriberInterface
{

    public function __construct(
        private ProcessorInterface     $persistProcessor,
        private ProcessorInterface     $removeProcessor,
        private ContainerInterface     $locator,
        private EntityManagerInterface $doctrine,
    )
    {
        
    }

    private \CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler $entityHandler;

    private EntityId $entityId;

    public function supports($data): bool
    {

        return false;
    }


    public function persist($entityId)
    {
        $this->entityHandler->save($entityId);
    }


    public function remove($entityId)
    {
        $this->entityHandler->delete($entityId);
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if ($data instanceof EntityId) {
            $reflectionClass = new ReflectionClass(get_class($data));
            $annot = $reflectionClass->getAttributes(EntityHandler::class);
            if ($annot) {
                /** @var \ReflectionAttribute $anotEntityHandler */
                $anotEntityHandler = $annot[0];
                $annotArgs = $anotEntityHandler->getArguments();
                $this->entityHandler = $this->locator->get($annotArgs[0]);
            }
            if ($operation instanceof Post || $operation instanceof Put) {
                $this->entityHandler->save($data);
            } elseif ($operation instanceof Delete) {
                $this->entityHandler->delete($data);
            }
        } else {
            $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }
        return $data;
    }

    public static function getSubscribedServices(): array
    {
        return [
            'App\EntityHandler\TorneioEntityHandler' => TorneioEntityHandler::class,
        ];
    }
}
