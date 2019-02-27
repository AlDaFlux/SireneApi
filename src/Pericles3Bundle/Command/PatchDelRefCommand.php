<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputOption;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchDelRefCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:delete-ref');
        $this->setDescription("Supprimme un patch");
        $this->setHelp("Supprimme un patch");
        $this->addOption('patch_id',null,InputOption::VALUE_REQUIRED,"L'identifiant du patch ",0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        
        $patchId = $input->getOption('patch_id');
        $patch = $em->getRepository("Pericles3Bundle:Patch")->findOneById($patchId);
        
        if (! $patch)
        {
            $output->writeln("<error>Le patch ".$patchId." n'exites pas<error>");
            
        }
        else
        {
            $output->writeln("Patch choisi : ");
            $output->writeln("--->".$patch);
            foreach ($patch->getPatchReferentiels() as $ref)
            {
                $output->writeln("Supression de <error>".$ref."</error>");
                $em->remove($ref);
                $em->flush();
            }
        }
    }
}
