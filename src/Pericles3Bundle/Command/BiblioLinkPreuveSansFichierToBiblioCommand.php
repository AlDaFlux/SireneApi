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


class BiblioLinkPreuveSansFichierToBiblioCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('biblio:link-preuves-sans-fichier-to-biblio');
        $this->setDescription("Les preuves dont les fichiers n'existe plus, sont lié avec la biblioteque de l'établissement ou du gestionnaire si elle existe");
        $this->setHelp("Les preuves dont les fichiers n'existe plus, sont lié avec la biblioteque de l'établissement ou du gestionnaire si elle existe");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
          
        $preuves =$em->getRepository('Pericles3Bundle:Preuve')->findAllFichier();

        //$preuves =$em->getRepository('Pericles3Bundle:Preuve')->findOneById(37763);

        chdir ("web");
        
        foreach ($preuves as $preuve)
        {
                if (! $preuve->GetFileExist())
                {
                     $output->writeln( $preuve->GetId()." : \"".$preuve->getRelativPath()."\" n'existe pas\n");
                     if ($preuve->getFileExistInBilioEtab())
                     {
                            $biblio=$em->getRepository('Pericles3Bundle:Bibliotheque')->FindBiblioByFile($preuve->GetEtablissement(),$preuve->GetFichier()) ;
                            if ($biblio)        
                            {
                                $preuve->SetBibliotheque($biblio);
                                $biblio->addPreufe($preuve);
                                $em->persist($preuve);
                                $em->persist($biblio);
                                $em->flush();
                                $output->writeln("<info>-->Preuve ".$preuve."- Biblio Etab ->".$biblio."\n</info>");
                            }
                            else
                            {
                                $output->writeln("<error>Le fichier existe dans biblio établissement  mais pas la biblio correspondante\n</error>");
                                copy($preuve->getFileExistInBilioEtab(), getcwd()."/upload/".$preuve->getRelativPath()); 
                                //unlink($preuve->getFileExistInBilioEtab()); 
                            }
                       
                     }
                     elseif ($preuve->getFileExistInBilioGestionnaire())
                     {
                          
                            $biblio=$em->getRepository('Pericles3Bundle:Bibliotheque')->FindBiblioGestionnaireByFile($preuve->GetEtablissement()->GetGestionnaire(),$preuve->GetFichier()) ;
                            if ($biblio)        
                            {
                                $preuve->SetBibliotheque($biblio);
                                $biblio->addPreufe($preuve);
                                $em->persist($preuve);
                                $em->persist($biblio);
                                $em->flush();
                                $output->writeln("<info>-->Preuve ".$preuve."- Biblio Gestionnaire ->".$biblio."\n</info>");
                            }
                            else
                            {
                                $output->writeln("<error>Le fichier existe dans dossier biblio gestionnaire mais pas la biblio correspondante\n</error>");
                                $output->writeln("<error>A faire : Voir plus haut ! \n</error>");
                            }
                     }
                    else 
                    {
                         
                         $output->writeln("<error>". $preuve->GetFichier()." non trouvé\n</error>");
                        
                    }
                }
                
               

             }
            
            
       
        
        $output->writeln("<info>tous preuves ont été liées aux bibliotheques</info>");
    }
}
