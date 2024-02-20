<?php

namespace RegisteredMailApp\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserInfoCommand extends UserRegisteredMailCommand
{
    protected static $defaultName = 'app:user-info';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $idUser = $input->getOption("id");
        $email = $input->getOption("email");

        try {
            $user = $this->userRegisteredMailService->getUserById($idUser);

            $user = [
                "id" => $user->getId(),
                "firstname" => $user->getData()["firstname"],
                "lastname" => $user->getData()["lastname"],
            ];
            $io->writeln(["-> " . json_encode($user)]);
            $io->success("User info");

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
        }
        return Command::FAILURE;
    }


    protected function configure(): void
    {
        $this
            ->setDescription("Permet de récupérer des informations d'un utilisateur")
            ->setHelp("{$this->getDescription()}");
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'User email to find ?')
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'User ID to find ?');
    }


}