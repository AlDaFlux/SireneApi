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
use Pericles3Bundle\Entity\StatUserConnect;
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
class UserStatCommand extends ArseneCommand
{
    // a good practice is to use the 'app:' prefix to group all your custom application commands
    protected static $defaultName = 'connection:stats';

    

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('connection:stats')
            ->setDescription('a utiliser qu une fois ! Remplis les données states avec les dernieres connections utilisateurs')
            ->setHelp(<<<'HELP'
The <info>%command.name%</info> command lists all the users registered in the application:

  <info>php %command.full_name%</info>

By default the command only displays the 50 most recent users. Set the number of
results to display with the <comment>--max-results</comment> option:
 <info>php %command.full_name%</info> <comment>--max-results=2000</comment>
 
HELP
            )
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see https://symfony.com/doc/current/components/console/console_arguments.html
            /*
            ->addOption('etablissement_id',null,InputOption::VALUE_REQUIRED,"Id de l'établissement",0)
            ->addOption('gestionnaire_id',null,InputOption::VALUE_REQUIRED,"ID du gestionnaire",0)
            ->addOption('creai_id',null,InputOption::VALUE_REQUIRED,"ID du CREAI",0)
            ->addOption('admin',null,InputOption::VALUE_NONE,"Liste les CT")
            ->addOption('gestionnaire',null,InputOption::VALUE_NONE,"Liste les utilisateurs établissements")
            ->addOption('etablissement',null,InputOption::VALUE_NONE,"Liste les utilisateurs gestionnaires")
             *  * 
             */
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


        $allUsers = $em->getRepository('Pericles3Bundle:User')->findAll();
        /*
        $allUsers = $em->getRepository('Pericles3Bundle:User')->FindByType("gestionnaire");
        $etablissement = $em->getRepository('Pericles3Bundle:User')->findOneById(466);
        $allUsers = $em->getRepository('Pericles3Bundle:User')->findByEtablissement($etablissement);
        */
        
        
        foreach ($allUsers as $user)
        {
            
            if ($user->getDateLastConnect())
            {
                $dateConnect=$user->getDateLastConnect();
                $output->writeln("<info>".$user." connecté le ".$dateConnect->format("Y-m-d")."</info>");
                $statUser=new StatUserConnect();
                $statUser->setUser($user);
		$this->GetEm()->persist($statUser);
		$this->GetEm()->flush();
                
                $gestionnaire=null;
                
                if ($user->isCreai())
                {
                    $creai=$user->getCreai();
                    if ($dateConnect>$creai->getDateLastConnect())
                    {
                        $creai->setDateLastConnect($dateConnect);
                        $this->GetEm()->persist($creai);
                        $this->GetEm()->flush();
                        $output->writeln("UPDATE CREAI");
                    }
                }
                elseif ($user->IsAnEtablissement())
                {
                    $etablissement=$user->getEtablissement();
                    if ($dateConnect>$etablissement->getDateLastConnect())
                    {
                        $etablissement->setDateLastConnect($dateConnect);
                        $this->GetEm()->persist($etablissement);
                        $this->GetEm()->flush();
                    }
                    $gestionnaire= $etablissement->getGestionnaire();
                    $output->writeln("UPDATE ETAB");
                }
                elseif ($user->isGestionnaire())
                {
                    $gestionnaire=$user->getGestionnaire();
                    $output->writeln("gest : ".$gestionnaire);
                    foreach ($user->GetEtablissements() as $etablissement  )
                    {
                        if ($dateConnect>$etablissement->getDateLastConnect())
                        {
                            $etablissement->setDateLastConnect($dateConnect);
                            $this->GetEm()->persist($etablissement);
                            $this->GetEm()->flush();
                        }
                    }
                }
                if ($gestionnaire)
                {
                    if ($dateConnect>$gestionnaire->getDateLastConnect())
                    {
                        $gestionnaire->setDateLastConnect($dateConnect);
                        $this->GetEm()->persist($gestionnaire);
                        $this->GetEm()->flush();
                        $output->writeln("UPDATE GESTIONNAIRE");
                    }
                }
            }
            else
            {
                    $output->writeln("<error>".$user." jamais connecté</error>");
            }
        }
    }
 
}
