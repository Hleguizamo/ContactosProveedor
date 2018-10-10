<?php

namespace Cpdg\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdministradorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario')
            ->add('nombre')
            ->add('email', 'email')
            ->add('contrasena', 'repeated', 
        array('type' => 'password', 'invalid_message' => 'La contraseña no coincide.',
        'first_options'  => array('label' => 'Contraseña'),
        'second_options' => array('label' => 'Confirme la contraseña'))) 
            ->add('convenio',  ChoiceType::class, array(
                'choices'  => array(
                    1 => 'Activo',
                    2 => 'Inactivo',
                ),
            ))       
            ->add('superadmin', CheckboxType::class, array(
                'label'    => 'Super Administrador',
                'required' => false,
            ))
            ->add('menuAdministradores', CheckboxType::class, array(
                'label'    => 'Control de Administradores',
                'required' => false,
            ))
            ->add('menuUsuarios', CheckboxType::class, array(
                'label'    => 'Control de Usuarios',
                'required' => false,
            ))
            ->add('menuContactos', CheckboxType::class, array(
                'label'    => 'Control de Contactos',
                'required' => false,
            ))
            ->add('menuProveedores', CheckboxType::class, array(
                'label'    => 'Control de Proveedores',
                'required' => false,
            ))
            ->add('menuAreas', CheckboxType::class, array(
                'label'    => 'Control de Departamentos',
                'required' => false,
            ))
            ->add('menuCargos', CheckboxType::class, array(
                'label'    => 'Control de Cargos',
                'required' => false,
            ))
            ->add('menuLogs', CheckboxType::class, array(
                'label'    => 'Control de Registro',
                'required' => false,
            ))
            ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cpdg\AdministradorBundle\Entity\Administrador'
        ));
    }
}
