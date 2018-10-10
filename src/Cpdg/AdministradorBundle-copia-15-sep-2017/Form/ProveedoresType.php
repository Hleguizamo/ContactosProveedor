<?php
namespace Cpdg\AdministradorBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class ProveedoresType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit')
            ->add('nombre')
            ->add('codigo')
            ->add('representanteLegal')
            ->add('emailRepresentanteLegal', 'email')
            ->add('telefonoRepresentanteLegal')
            ->add('convenio',  ChoiceType::class, array(
                'choices'  => array(
                    1 => 'Activo',
                    0 => 'Inactivo',
                ),
                //'data' => 0
            ))
            ->add('fechaInicio', DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
                'attr'=>array(
                    'class'=>'date'
                ),
                'required'=>false

            ))  
            ->add('fechaFin', DateType::class, array(
                // render as a single text box
                'widget' => 'single_text',
                'attr'=>array(
                    'class'=>'date'
                ),
                'required'=>false

            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cpdg\AdministradorBundle\Entity\Proveedores'
        ));
    }
}
