<?php


namespace CrosierSource\CrosierLibCoreBundle\Doctrine\Listeners;


use CrosierSource\CrosierLibCoreBundle\Business\Config\SyslogBusiness;
use CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\TrackDateOnly;
use CrosierSource\CrosierLibCoreBundle\Entity\EntityId;
use CrosierSource\CrosierLibCoreBundle\Entity\Security\User;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * @author Carlos Eduardo Pauluk
 */
class PreUpdateListener
{

    private ManagerRegistry $doctrine;

    private SyslogBusiness $syslog;

    private Security $security;

    public function __construct(ManagerRegistry $doctrine, SyslogBusiness $syslog, Security $security)
    {
        $this->doctrine = $doctrine;
        $this->syslog = $syslog->setApp('libbase')->setComponent(self::class);
        $this->security = $security;
    }


    public function preUpdate(PreUpdateEventArgs $args)
    {
        return;
        /** @var EntityId $entity */
        $entity = $args->getObject();

        try {
            $entityManager = $this->doctrine->getManager();
            $cache = new FilesystemAdapter($_SERVER['CROSIERAPP_ID'] . '.cache', 0, $_SERVER['CROSIER_SESSIONS_FOLDER']);
            $classes = $cache->get('trackedEntities', function () use ($entityManager) {
                $all = $entityManager->getMetadataFactory()->getAllMetadata();
                $annotationReader = new AnnotationReader();

                $classes = [];
                foreach ($all as $classMeta) {
                    $reflectionClass = $classMeta->getReflectionClass();
                    if ($annotationReader->getClassAnnotation($reflectionClass, 'CrosierSource\CrosierLibCoreBundle\Doctrine\Annotations\TrackedEntity')) {
                        $class = [];
                        $class['name'] = $classMeta->getName();
                        foreach ($classMeta->reflFields as $field => $reflField) {
                            if ($annotationReader->getPropertyAnnotation($reflField, TrackDateOnly::class)) {
                                $class['trackDateOnly'][] = $field;
                            }
                        }
                        $classes[] = $class;
                    }
                }
                return $classes;
            });

            $isTrackedClass = false;
            foreach ($classes as $class) {
                if ($class['name'] === get_class($entity)) {
                    $isTrackedClass = true;
                    break;
                }
            }

            if ($isTrackedClass) {
                $strChanges = '';
                /** @var array $entityChangeSet */
                $entityChangeSet = $args->getEntityChangeSet();
                foreach ($entityChangeSet as $field => $changes) {
                    if ($field === 'updated') continue;
                    foreach ($changes as $k => $v) {
                        if ($v instanceof \DateTime || $v instanceof \DateTimeImmutable) {
                            if (in_array($field, $class['trackDateOnly'] ?? [], true)) {
                                $changes[$k] = $v->format('d/m/Y');
                            } else {
                                $changes[$k] = $v->format('d/m/Y H:i:s T');
                            }
                        } elseif ($v instanceof EntityId) {
                            $changes[$k] = $v->__toString();
                        } elseif (is_bool($v)) {
                            $changes[$k] = $v ? 'SIM' : 'NÃO';
                        } elseif (is_numeric($v)) {
                            $changes[$k] = (float)$v;
                        } elseif (!is_array($v)) {
                            $changes[$k] = (string)$v;
                        } elseif ($field === 'jsonData') { //  && is_array($v)) {
                            continue; // será tratado ali abaixo
                        } else {
                            throw new \InvalidArgumentException('tipo de campo não tratável no PreUpdateListener');
                        }
                    }
                    if ($field === 'jsonData') {
                        $arrDiff0_1 = self::array_diff_assoc_recursive($changes[0], $changes[1]);
                        $arrDiff1_0 = self::array_diff_assoc_recursive($changes[1], $changes[0]);
                        $arrDiff = array_merge_recursive($arrDiff0_1, $arrDiff1_0);
                        ksort($arrDiff);
                        foreach ($arrDiff as $k => $diff) {
                            $from = is_array($diff) ? json_encode($changes[0][$k] ?? []) : ($changes[0][$k] ?? '_NULL_');
                            $to = is_array($diff) ? json_encode($changes[1][$k] ?? []) : ($changes[1][$k] ?? '_NULL_');
                            $strChanges .= 'jsonData.' . $k . ': de "' . $from . '" para "' . $to . '"' . PHP_EOL;
                        }
                    } else if ((string)$changes[0] !== (string)$changes[1]) {
                        $strChanges .= $field . ': de "' . $changes[0] . '" para "' . $changes[1] . '"' . PHP_EOL;
                    }

                }
                if ($strChanges) {
                    $changingUserId = $entity->getUserUpdatedId();

                    /** @var User $user */
                    if ($this->security->getUser()) {
                        $user = $this->security->getUser();
                    } else {
                        $user = $cache->get('PreUpdateListener.getUser_' . $changingUserId, function () use ($changingUserId) {
                            $repoUser = $this->doctrine->getRepository(User::class);
                            return $repoUser->find($changingUserId);
                        });
                    }

                    $class = str_replace("\\", ":", get_class($entity));

                    $entityChange = [
                        'entity_class' => $class,
                        'entity_id' => $entity->getId(),
                        'changing_user_id' => $changingUserId,
                        'changing_user_username' => $user->username,
                        'changing_user_nome' => $user->nome,
                        'changed_at' => $entity->getUpdated()->format('Y-m-d H:i:s'),
                        'changes' => $strChanges,
                    ];

                    try {
                        $entityManagerLogs = $this->doctrine->getManager('logs');
                        $conn = $entityManagerLogs->getConnection();
                        $conn->insert('cfg_entity_change', $entityChange);
                        $this->syslog->info('Alteração em ' . get_class($entity) . ' id: (' . $entity->getId() . ')');
                    } catch (\Exception $e) {
                        throw new \RuntimeException('Erro ao salvar na cfg_entity_change para ' . get_class($entity));
                    }
                }
            }
        } catch (\Throwable $e) {
            throw new \RuntimeException('Erro no PreUpdateListener para ' . get_class($entity), 0, $e);
        }
    }

    private static function array_diff_assoc_recursive($array1, $array2)
    {
        $array1 = is_array($array1) ? $array1 : [$array1];
        $array2 = is_array($array2) ? $array2 : [$array2];
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key])) {
                    $difference[$key] = $value;
                } elseif (!is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = self::array_diff_assoc_recursive($value, $array2[$key]);
                    if ($new_diff != FALSE) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!array_key_exists($key, $array2) || $array2[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return !isset($difference) ? [] : $difference;
    }

}
