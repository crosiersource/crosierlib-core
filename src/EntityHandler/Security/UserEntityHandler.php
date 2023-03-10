<?php

namespace CrosierSource\CrosierLibCoreBundle\EntityHandler\Security;

use CrosierSource\CrosierLibCoreBundle\Business\Config\SyslogBusiness;
use CrosierSource\CrosierLibCoreBundle\Entity\Config\AppConfig;
use CrosierSource\CrosierLibCoreBundle\Entity\Security\Role;
use CrosierSource\CrosierLibCoreBundle\Entity\Security\User;
use CrosierSource\CrosierLibCoreBundle\EntityHandler\EntityHandler;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @author Carlos Eduardo Pauluk
 */
class UserEntityHandler extends EntityHandler
{

    private UserPasswordHasherInterface $passwordEncoder;


    public function __construct(ManagerRegistry              $doctrine,
                                Security                     $security,
                                ParameterBagInterface        $parameterBag,
                                SyslogBusiness               $syslog,
                                UserPasswordHasherInterface $passwordEncoder)
    {
        parent::__construct($doctrine, $security, $parameterBag, $syslog->setApp('core')->setComponent(self::class));
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param User $user
     * @return mixed|void
     */
    public function beforeSave($user)
    {
        if (is_array($user->userRoles)) {
            $roles = $user->userRoles;
            $user->userRoles = new ArrayCollection();
            foreach ($roles as $role) {
                $user->userRoles->add($role);
            }
        }
        /** @var User $user */
        if ($user->password && strlen($user->password) < 53) {
            $encoded = $this->passwordEncoder->hashPassword($user, $user->password);
            $user->password = $encoded;
        } elseif ($user->getId() && !$user->password) {
            $savedPassword = $this->doctrine->getRepository(User::class)->getPassword($user);
            $user->password = $savedPassword;
        }
        $user->email = mb_strtolower($user->email);
        $user->username = mb_strtolower($user->username);
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function renewTokenApi(User $user)
    {
        if (!$user->apiToken) {
            $user->apiToken = bin2hex(random_bytes(60));
        }
        $user->apiTokenExpiresAt = new \DateTime('+1680 hour');
        $this->save($user);
        return $user->apiToken;
    }

    /**
     * @param User $user
     * @throws \CrosierSource\CrosierLibCoreBundle\Exception\ViewException
     */
    public function revogarApiToken(User $user): void
    {
        $user->apiToken = null;
        $this->save($user);
    }

    /**
     * Verifica e conserta as roles de um usu??rio (ex.: usu??rio que seja ROLE_ADMIN deve ter tamb??m todas as outras roles)
     *
     * @param User $user
     */
    public function fixRoles(User $user): void
    {
        $repoAppConfig = $this->getDoctrine()->getRepository(AppConfig::class);
        $rolesNotForTheAdmin = [];
        /** @var AppConfig $appConfig */
        $appConfig = $repoAppConfig->findOneByFiltersSimpl([['chave', 'EQ', 'ROLES_NOT_FOR_THE_ADMIN']]);
        if ($appConfig) {
            $rolesNotForTheAdmin = explode(',', $appConfig->valor);
        }
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $todas = $this->getDoctrine()->getRepository(Role::class)->findAll();
            /** @var Role $role */
            foreach ($todas as $role) {
                if (!in_array($role->role, $rolesNotForTheAdmin, true)) {
                    if (!in_array($role->role, $user->getRoles())) {
                        $user->userRoles->add($role);
                    }
                } else {
                    if ($user->userRoles->contains($role)) {
                        $user->userRoles->remove($user->userRoles->indexOf($role));
                    }
                }
            }
            $this->save($user);
        }
    }


    public function beforeDelete(/** @var User $usuario */ $user)
    {
        // Para poder deletar, preciso primeiro remover a poss??vel auto-refer??ncia do usu??rio a ele mesmo
        // para poder passar no FOREIGN KEY
        if ((int)$user->getUserUpdatedId() === (int)$user->getId()) {
            $user->setUserUpdatedId(1);
            $this->save($user);
        }
    }

    public function getEntityClass()
    {
        return User::class;
    }
}