<?php
namespace Cpdg\AdministradorBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ajusteCargosCommand extends ContainerAwareCommand{

    protected function configure(){
        parent::configure();
        $this->setName('ajustar:cargos')->setDescription('ajusta los cargos asignados a los proveedores');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $em = $this->getContainer()->get('doctrine')->getManager();
        $connect = $em->getConnection();
        
        $output->writeln("Inicia la tarea.");

        $sqlProveedores = "SELECT * FROM proveedores ";
        $proveedores = $connect->query($sqlProveedores);

        foreach($proveedores as $proveedor){

            $sqlContactos = "SELECT id, id_cargo FROM contactos WHERE id_proveedor =".$proveedor['id']." GROUP BY id_cargo ";
            $cargosSeleccionados = $connect->query($sqlContactos);


            $sqlInsert = "INSERT INTO cargos_proveedor (id_proveedor, id_cargo) VALUES ";
            $sqlValues = "";

            foreach($cargosSeleccionados as $cargo){
                if($sqlValues != ""){
                    $sqlValues .= ",";
                }

                $sqlValues .= "('".$proveedor['id']."','".$cargo['id_cargo']."')";

            }

            $output->writeln($sqlInsert.$sqlValues);
            

            if($sqlValues != ""){
                $connect->query($sqlInsert.$sqlValues);
                $sqlValues = "";
            }



        }
        


        $output->writeln("Fin de la tarea.");

    }

}
?>