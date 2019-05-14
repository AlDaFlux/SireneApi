<?php
// myapplication/src/sandboxBundle/Command/TestCommand.php
// Change the namespace according to your bundle
namespace Pericles3Bundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
    
    
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Input\ArrayInput;
use DateTime;



use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;


class PatchTodoBatchCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('patch:patch-todo-batch');
        $this->setDescription("Patch tous les établissement...");
        $this->setHelp("Patch tous les établissement... attention dangereux !");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        /** @var \Doctrine\ORM\EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $em->getConnection();
        $connection->getConfiguration()->setSQLLogger(null);

        /** @var \App\Repository\User $users */
        $users = $em->getRepository('Pericles3Bundle:User')->findAll();

        // There are a lot of rows to migrate, so we migrate them little
        // by little to use less RAM. You can tweak this depending on
        // what you find works best for your use case.
        $batchSize = 100;

        // The fastest way to mimic an OFFSET clause is to store the last
        // migrated User.id and to select rows above that ID instead of
        // using an actual OFFSET.
        $minimumId = 0;

        do {
            list($batchCount, $minimumId) = $this->runOneBatch($em, $users, $style, $batchSize, $minimumId);
        } while ($batchCount === $batchSize);
    }

    function runOneBatch(EntityManagerInterface $em, UserRepository $users, SymfonyStyle $style, int $batchSize, int $minimumId): array {
        $batch = $users->getBatchOfUsersOrderedById($batchSize);
        $batchCount = count($batch);

        if ($batchCount > 0) {
            $minimumId = $batch[$batchCount - 1]->getId();

            /** @var User $user */
            foreach ($batch as $user) {
                try {
                    // Do some stuff with the current user, for instance
                    // set a newly added field to "false"
                    $user->setAccessRestricted(false);
                    $user->setUsername("----".$user->getUsername());
                    $style->text("Batch finished with memory: ${memoryUsage}M");
                } catch (\Exception $e) {
                    // Handle the exception if needed
                    // ...

                    // Once the exception is handled, it is no longer needed
                    // so we can mark it as useless and garbage-collectable
                    $e = null;
                    unset($e);
                }

                $em->persist($user);

                // We are done updating this user, so we mark it as unused,
                // this way PHP can remove it from memory
                $user = null;
                unset($user);
            }

            // For each batch of users, we display the memory usage in MB so
            // that we can see if it grows during testing: if it does grow,
            // there is most likely a memory leak somewhere
            $memoryUsage = memory_get_usage(true) / 1024 / 1024;
            $style->text("Batch finished with memory: ${memoryUsage}M");

            // Once the batch of users is updated, we don't need it anymore
            // so we mark it as garbage-collectable
            $batch = null;
            unset($batch);

            // Flushing and then clearing Doctrine's entity manager allows
            // for more memory to be released by PHP
            $em->flush();
            $em->clear();

            // Just in case PHP would choose not to run garbage collection,
            // we run it manually at the end of each batch so that memory is
            // regularly released
            gc_collect_cycles();
        }

        return [$batchCount, $minimumId];
    }

}
