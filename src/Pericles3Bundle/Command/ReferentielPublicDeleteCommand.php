<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArrayInput;

use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class ReferentielPublicDeleteCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('referentiel:public:delete');
        $this->setDescription('suprimme un Référentiel !!! !Atention ');
        $this->setHelp("suprimme un Référentiel !!! !Atention ");
        $this->addOption('referentiel_public_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du référentiel public ",0);
        $this->addOption('force-delete-patch',null,InputOption::VALUE_NONE,"supprimme les patchs si il y en a ");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
                
        $forcePatch = $input->getOption('force-delete-patch');

                
        $em = $doctrine->getEntityManager();
        $refPublicId = $input->getOption('referentiel_public_id');
        $refPublic = $em->getRepository("Pericles3Bundle:ReferentielPublic")->findOneById($refPublicId);
        if (! $refPublic)
        {
            $output->writeln("<error>Le référentiel Public  ".$refPublicId." n'exites pas<error>");
        }
        else
        {         
            $output->writeln("referentiel choisi : ");
            $output->writeln("<info>".$refPublic."</info>");
            if ($refPublic->getNbEtablissements())
            {
                $output->writeln("<error>Le référentiel Public  ".$refPublic." a ".$refPublic->getNbEtablissements()." établissements il est donc impossible a suprimmé</error>");
                return(0);
            }
            if ($refPublic->hasPatch())
            {
                $output->writeln("<error>Le référentiel Public  ".$refPublic." a des patchs </error>");
                if ($forcePatch)
                {
                        $command = $this->getApplication()->find('patch:delete-full');
                        foreach ($refPublic->GetPatchAll() as $patch)
                        {
                            $arguments = array('command' => 'patch:delete-full','--patch_id'  => $patch->GetId());
                            $PatchEtabInput = new ArrayInput($arguments);
                            $command->run($PatchEtabInput, $output);
                        }
                        
                        

                }
                else
                {
                    $output->writeln("<error>-- il est donc impossible a suprimmé</error>");
                    $output->writeln("<info>--force-delete-patch </info>");
                    return(0);
                }
            }

            
            $output->writeln("<info>Uilisateurs</info>");
            foreach ($refPublic->GetUsers() as $user)
            {
                $output->writeln("<error>User  ".$user." </error>");
                $user->removeReferentielsPublic($refPublic);
                $em->persist($user);
                $em->flush();
            }
            
            $output->writeln("<info>Biblio</info>");
            
            foreach ($refPublic->getBibliothequesAncreai() as $biblio)
            {
                $output->writeln("<error>Biblio ".$biblio." <error>");
                $biblio->removeReferentielPublic($refPublic);
                $em->persist($biblio);
                $em->flush();
            }
            
            $output->writeln("<info>Enfants</info>");
            foreach ($refPublic->getSourceChildren() as $refEnfant)
            {
                $output->writeln("<error>Enfant ".$refEnfant." <error>");
                $refEnfant->setSourceParent(null);
                $em->persist($refEnfant);
                $em->flush();
            }
            
            $output->writeln("<info>Parent</info>");
            $refPublic->setSourceParent(null);
            
            foreach ($refPublic->getVersionningChildren() as $refVersionEnfant)
            {
                $refVersionEnfant->setVersionningParent($refPublic->getVersionningParent());
                $em->persist($refVersionEnfant);
                $em->flush();
            }
            
            
            
            
            
            
    foreach ($refPublic->getReferentielDomaines() as $Domaine )
    {
               foreach ($Domaine->getDimensions() as $Dimension  )
               {
                   foreach ($Dimension->getCriteres() as $Critere )
                   {
                       foreach ($Critere->GetQuestions() as $Question)
                       {
                            $em->remove($Question);
                       }
                       $em->flush();
                       $em->remove($Critere);
                   }
                   $em->flush();
                   $em->remove($Dimension);
               }
               $em->flush();
               $em->remove($Domaine);
        }
        
        $em->flush();
        
        $output->writeln("<info>Supression de tous les domaines </info>");

        $em->remove($refPublic);
        $em->flush();
  
            
            
        }
    }
}
