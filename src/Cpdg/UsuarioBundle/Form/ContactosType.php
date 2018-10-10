<?php

namespace Cpdg\UsuarioBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 

class ContactosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
            ->add('idProveedor', EntityType::class, array(
                'class' => 'CpdgUsuarioBundle:Proveedores',
                'choice_label' => 'nombre',
                'label' => "Empresa / Razón Social: ",
            ))
            ->add('nombreContacto')
            ->add('idCargo', EntityType::class, array(
                'class' => 'CpdgUsuarioBundle:Cargos',
                'choice_label' => 'nombre',
                'label' => "Rol: ",
            ))
            ->add('ciudad')
            ->add('email','email')
            ->add('telefono')
            ->add('movil')
            ->add('idArea', EntityType::class, array(
                'class' => 'CpdgUsuarioBundle:Areas',
                'choice_label' => 'nombre',
                'label' => "Departamento: ",
            ))
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cpdg\UsuarioBundle\Entity\Contactos'
        ));
    }
}
