<?php

namespace RegisteredMailApp\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserListCommand extends UserRegisteredMailCommand
{
    protected static $defaultName = 'app:user-list';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $users = $this->userRegisteredMailService->getUsers();

            $i = 1;
            foreach ($users as $user) {
                $user = [
                    "id" => $user->getId(),
                    "firstname" => $user->getData()["firstname"],
                    "lastname" => $user->getData()["lastname"],
                ];

                $io->writeln([
                    $i . ") -> " . json_encode($user),
                ]);
                $i++;
            }
            $io->success("Users list count " . count($users));

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
        }
        return Command::FAILURE;
    }


    protected function configure(): void
    {
        $this
            ->setDescription("Permet de lister les utilisateurs créés")
            ->setHelp("{$this->getDescription()}");
    }


}