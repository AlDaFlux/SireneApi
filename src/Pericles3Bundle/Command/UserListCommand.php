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
class UserListCommand extends ContainerAwareCommand
{
    // a good practice is to use the 'app:' prefix to group all your custom application commands
    protected static $defaultName = 'user:list';

    

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('user:list')
            ->setDescription('Lists all the existing users')
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

        // Doctrine query returns an array of objects and we need an array of plain arrays
        $usersAsPlainArrays = array_map(function (User $user) {
            if ($user->IsAnEtablissement())
            {
                return [
                    $user->getId(),
                    $user->getEtablissement(),
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->getRolePrincipal()
                ];
            }
            elseif ($user->isGestionnaire())
            {
                return [
                    $user->getId(),
                    $user->getGestionnaire(),
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->getRolePrincipal()
                ];
            }
            else
            {
                return [
                    $user->getId(),
                    $user->getCreai(),
                    $user->getUsername(),
                    $user->getEmail(),
                    $user->getRolePrincipal()
                ];
            }
            
        }, $allUsers);

        // In your console commands you should always use the regular output type,
        // which outputs contents directly in the console window. However, this
        // command uses the BufferedOutput type instead, to be able to get the output
        // contents before displaying them. This is needed because the command allows
        // to send the list of users via email with the '--send-to' option
        $bufferedOutput = new BufferedOutput();
        $io = new SymfonyStyle($input, $bufferedOutput);
        $io->table(
            ['ID',  'Etablissement',  'Username','Email', 'Roles'],
            $usersAsPlainArrays
        );

        // instead of just displaying the table of users, store its contents in a variable
        $usersAsATable = $bufferedOutput->fetch();
        $output->write($usersAsATable);
    }
 
}
