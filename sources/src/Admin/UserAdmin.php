<?php

namespace App\Admin;

use App\DBAL\Types\UserRoleType;
use App\Entity\User;
use App\Security\AdminUserVoter;
use App\Service\UserManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

/**
 * Class DomainAdmin
 */
class UserAdmin extends AbstractAdmin
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * Set user manager
     */
    public function setUserManager(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * Encode user's password on create
     *
     * @param User $user
     */
    public function prePersist($user)
    {
        $this->userManager->updatePassword($user);
    }

    /**
     * Send message to user that acount was created
     *
     * @param User $user
     */
    public function postPersist($user)
    {
        $this->userManager->notificationNewUserWasCreated($user);
    }

    /**
     * Encode user's password on update
     *
     * @param User $user
     */
    public function preUpdate($user)
    {
        $this->userManager->updatePassword($user);
    }

    /**
     * Configure filters
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('id')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('role', 'doctrine_orm_choice', [], ChoiceType::class, [
                'required' => true,
                'choices' => UserRoleType::getReadableValues(),
            ])
//            ->add('status', 'doctrine_orm_choice', [], ChoiceType::class, [
//                'choices' => UserStatus::getChoices(),
//            ])
            ->add('createdAt', 'doctrine_orm_date', [], null, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off',
                ],
            ]);
    }

    /**
     * Configure list fields
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('role', ChoiceType::class, [
                'choices' => UserRoleType::getReadableValues(),
            ])
            ->add('createdAt')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    /**
     * Configure show fields
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('id')
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('role', ChoiceType::class, [
                'required' => true,
                'choices' => UserRoleType::getReadableValues(),
            ])
            ->add('createdAt');
    }

    /**
     * Configure form fields
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => $this->isCurrentRoute('create'),
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ]);
//            ->add('status');

        if ($this->isGranted(AdminUserVoter::ADMIN_USERS_CHANGE_ROLE, $this->getSubject())) {
            $form->add('role', ChoiceType::class, [
                'required' => true,
                'choices' => array_flip(UserRoleType::getReadableValues()),
            ]);
        }
    }

    /**
     * Configure batch actions
     *
     * @param array $actions
     *
     * @return array
     */
    protected function configureBatchActions($actions)
    {
        return [];
    }

    /**
     * Configure routes
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('export');
    }
}
