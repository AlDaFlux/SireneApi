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
class TmpUserSignFirstCguCommand extends ContainerAwareCommand
{
    // a good practice is to use the 'app:' prefix to group all your custom application commands
    protected static $defaultName = 'user:sign-first-cgu';

    

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('user:sign-first-cgu')
            ->setDescription('Lists all the existing users')
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
        $allUsers = $em->getRepository('Pericles3Bundle:User')->findAll();
        
                
        $firstCGU= $em->getRepository('Pericles3Bundle:EditorialCLU')->findFirst();        

        foreach ($allUsers as $user)
        {
            if ($user->getLastCluChecked()==$firstCGU)
            {
                $output->writeln("<info>".$user."</info>"); 
            }
            else
            {
                if ($user->GetConditionsAcceppted())
                {
                    $output->writeln("<error>".$user."</error>"); 
                    $user->setLastCluChecked($firstCGU);
                    $em->persist($user);
                }
                else
                {
                    $output->writeln("<info>".$user."</info>"); 
                }
            }
        }
        $em->flush();

   
    }
 
}
