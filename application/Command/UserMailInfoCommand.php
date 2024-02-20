<?php

namespace RegisteredMailApp\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserMailInfoCommand extends UserRegisteredMailCommand
{
    protected static $defaultName = 'app:user-mail-info';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $idMail = $input->getOption("id");

        try {
            $mail = $this->userRegisteredMailService->getMailInfo($idMail);

            $mail = [
                "id" => $mail->getId(),
                "content" => $mail->getData()["pdf_content"] . " octets",
            ];
            $io->writeln(["-> " . json_encode($mail)]);
            $io->success("User mail info");

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
        }
        return Command::FAILURE;
    }


    protected function configure(): void
    {
        $this
            ->setDescription("Permet de récupérer des informations d'un courrier")
            ->setHelp("{$this->getDescription()}");
        $this
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'Registered mail ID to find ?');
    }

}