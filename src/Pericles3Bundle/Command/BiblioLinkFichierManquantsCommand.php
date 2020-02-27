<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class BiblioLinkFichierManquantsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('biblio:fichiers-manquants');
        $this->setDescription("Liste les fichiers manquants ");
        $this->setHelp("Liste les fichiers manquants ");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        chdir ("web");
         
        $preuves =$em->getRepository('Pericles3Bundle:Preuve')->findAllFichier();
        foreach ($preuves as $preuve)
        {
            if (! $preuve->GetFileExist())
            {
                $output->writeln("Recheche Fichier : ".$preuve->getFichier());
                $result=shell_exec("locate  -r   \"/".$preuve->getFichier()."$\"");
                if ($result)
                {
                    $output->writeln("<info>\n".$result."</info>");
                }
                else
                {
                    $output->writeln("<error>Le fichier n'existe pas \n</error>");
                }
            }
        } 
        
        
        $biblios =$em->getRepository('Pericles3Bundle:Bibliotheque')->findFichiers();
        $biblios_manquant= new \Doctrine\Common\Collections\ArrayCollection(); 
        foreach ($biblios as $biblio)
        {
            if (! $biblio->getFileExist())
            {
                $output->writeln("<info>\n\n\n---------------</info>");
                $output->writeln("Biblio - recheche Fichier : ".$biblio->getFichier());
                 $result=shell_exec("locate  -r   \"/".$biblio->getFichier()."$\"");
                if ($result)
                {
                    $output->writeln("<info>\n".$result."</info>");
                    $output->writeln("<info>\n ---> ".$biblio->getRelativPath()."</info>");
//                    $output->writeln("<info>\n ----------> cp \"".$result."\" \"".$biblio->getRelativPath()."\"</info>");
                }
                else
                {
                    $output->writeln("<error>Le fichier n'existe pas \n</error>");
                }
            }
        }

        
        
        

    }
}
