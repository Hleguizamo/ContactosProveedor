<?php

namespace Cpdg\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('idArea', EntityType::class, array(
                'class' => 'CpdgAdministradorBundle:Areas',
                'choice_label' => 'nombre',
                'label' => "Departamento: ",
            ))
            ->add('convenio',  ChoiceType::class, array(
                'choices'  => array(
                    1 => 'Activo',
                    2 => 'Inactivo',
                ),
            )) 
            ->add('usuario')
            ->add('nombre')
            ->add('email','email')
            ->add('contrasena', 'repeated', 
                array('type' => 'password', 'invalid_message' => 'La contraseña no coincide.',
                    'first_options'  => array('label' => 'Contraseña'),
                    'second_options' => array('label' => 'Confirme la contraseña')))   

        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cpdg\AdministradorBundle\Entity\Usuario'
        ));
    }
}
