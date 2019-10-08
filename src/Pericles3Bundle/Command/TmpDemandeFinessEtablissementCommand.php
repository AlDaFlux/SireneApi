<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pericles3Bundle\Command;

use Pericles3Bundle\Entity\User;
use Pericles3Bundle\Repository\UserRepository;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


/**
 * A console command that lists all the existing users.
 *
 * To use this command, open a terminal window, enter into your project directory
 * and execute the following:
 *
 *     $ php bin/console app:list-users
 *
 * See https://symfony.com/doc/current/cookbook/console/console_command.html
 * For more advanced uses, commands can be defined as services too. See
 * https://symfony.com/doc/current/console/commands_as_services.html
 *
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class TmpDemandeFinessEtablissementCommand extends ContainerAwareCommand
{
    // a good practice is to use the 'app:' prefix to group all your custom application commands
    protected static $defaultName = 'demande:affecte-etab-from-finess';

    

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('demande:affecte-etab-from-finess')
            ->setDescription('Affescte les établiessements aux demande à partir du finess')
            ->setHelp(<<<'HELP'
The <info>%command.name%</info> command lists all the users registered in the application:

  <info>php %command.full_name%</info>

By default the command only displays the 50 most recent users. Set the number of
results to display with the <comment>--max-results</comment> option:
 <info>php %command.full_name%</info> <comment>--max-results=2000</comment>
 
HELP
            )
            
        ;
            
    }

    /**
     * This method is executed after initialize(). It usually contains the logic
     * to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager();
        $demandes = $em->getRepository('Pericles3Bundle:DemandeEtablissement')->findAll();
        
        foreach ($demandes as $demande)
        {
            if ($demande->GetEtablissement())
            {
                $output->writeln("<info>-->".$demande."</info>"); 
            }
            else
            {
                $output->writeln("<error>--".$demande."</error>"); 
                if ($demande->GetFiness())
                {
                    if ($demande->GetFiness()->GetEtablissement())
                    {
                        $output->writeln("<info>-------->".$demande->GetFiness()->GetEtablissement()."</info>"); 
                        $demande->SetEtablissement($demande->GetFiness()->GetEtablissement());
                        $em->persist($demande);
                        $em->flush();
                        
                    }
                    else
                    {
                        $output->writeln("<error>-----Pas d'établissement</error>"); 
                    }
                }
                else 
                {
                         $output->writeln("<error>-----Pas de FINESS</error>"); 

                }
                
            }
        }
        $em->flush();
   
    }
 
}
