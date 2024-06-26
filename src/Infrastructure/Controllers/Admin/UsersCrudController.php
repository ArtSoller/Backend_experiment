<?php

namespace App\Infrastructure\Controllers\Admin;

use App\Infrastructure\Database\Entity\Roles;
use App\Infrastructure\Database\Entity\Users;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UsersCrudController extends AbstractCrudController
{
    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            TextField::new('email'),
            TextField::new('password')
                ->setFormType(PasswordType::class)
                ->onlyOnForms(),
            AssociationField::new('user_roles')
                ->setFormTypeOptions([
                    'class' => Roles::class,
                    'choice_label' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                ])
                ->formatValue(function ($value, $entity) {
                    return implode(', ', $entity->getUserRoles()->map(function ($role) {
                        return $role->getName();
                    })->toArray());
                }),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $plainPassword = $entityInstance->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
        $entityInstance->setPassword($hashedPassword);

        parent::persistEntity($entityManager, $entityInstance);
    }
}