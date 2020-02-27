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
class UserPromoteCommand extends ContainerAwareCommand
{
    // a good practice is to use the 'app:' prefix to group all your custom application commands
    protected static $defaultName = 'user:promote';

    

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('user:promote')
            ->setDescription('promote a user ')
            ->setHelp(<<<'HELP'
The <info>%command.name%</info> command lists all the users registered in the application:

  <info>php %command.full_name%</info>

By default the command only displays the 50 most recent users. Set the number of
results to display with the <comment>--max-results</comment> option:
 <info>php %command.full_name%</info> <comment>-username=prenoim.nom</comment>
 
HELP
            )
            // commands can optionally define arguments and/or options (mandatory and optional)
            // see https://symfony.com/doc/current/components/console/console_arguments.html
            
            ->addOption('username',null,InputOption::VALUE_REQUIRED,"Nom prénom de l'établissement",0)
            ->addOption('role',null,InputOption::VALUE_REQUIRED,"Roles",0)
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

        $username = $input->getOption('username');
        $role_choisi = $input->getOption('role');
        $user = $em->getRepository('Pericles3Bundle:User')->findOneByUsername($username);
        
        $existe_deja=false;
        if ($user)
        {
            $output->writeln("Roles");
            foreach ($user->GetRoles() as $role)
            {
                if ($role==$role_choisi)
                {
                    $existe_deja=true;
                    $output->writeln("<error> -> ".$role."  </error>");
                }
                else
                {
                    $output->writeln("<info> -> ".$role."  </info>");
                }
            }
            
            if ($existe_deja)
            {
                    $output->writeln("<error> -> ".$role_choisi." est déja affecté à ".$user."  </error>");
            }
            else
            {
                $user->AddRole($role_choisi);
                $em->persist($user);
                $em->flush();
                $output->writeln("<info> -> ".$role_choisi." affecté à ".$user."   </info>");
            }
        }
        else
        {
            $output->writeln("<error> L'utilisateur ".$username." n'existe pas </error>");
        }
        
        
        
  
    }
 
}
