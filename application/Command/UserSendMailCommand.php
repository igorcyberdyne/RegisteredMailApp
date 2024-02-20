<?php

namespace RegisteredMailApp\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserSendMailCommand extends UserRegisteredMailCommand
{
    protected static $defaultName = 'app:user-send-mail';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $idUser = $input->getOption("id");
        $to_email = $input->getOption("to_email");
        $to_firstname = $input->getOption("to_firstname");
        $to_lastname = $input->getOption("to_lastname");
        $dest_statut = $input->getOption("dest_statut");
        $content = $input->getOption("content");
        $attachmentId = $input->getOption("attachment");

        try {
            $mail = $this->userRegisteredMailService->sendMailTo([
                'id_user' => $idUser,
                'to_firstname' => $to_firstname,
                'to_lastname' => $to_lastname,
                'to_email' => $to_email,
                'dest_statut' => $dest_statut,
                'content' => $content,
                'attachment[0]' => $attachmentId,
            ]);

            $mail = [
                "id" => $mail->getId(),
                "content" => $mail->getData()["pdf_content"] . " octets",
            ];
            $io->writeln(["-> " . json_encode($mail)]);
            $io->success("Send recipient to mail");

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
        }
        return Command::FAILURE;
    }


    protected function configure(): void
    {
        $this
            ->setHelp("This command help to {$this->getDescription()}");
        $this
            ->addOption('id', "i", InputOption::VALUE_REQUIRED, "Sender's user ID")
            ->addOption('to_email', "e", InputOption::VALUE_REQUIRED, "Recipient's email")
            ->addOption('to_firstname', "f", InputOption::VALUE_REQUIRED, "Recipient's firstname")
            ->addOption('to_lastname', "l", InputOption::VALUE_REQUIRED, "Recipient's lastname")
            ->addOption('dest_statut', "s", InputOption::VALUE_REQUIRED, "Recipient's statut")
            ->addOption('content', "c", InputOption::VALUE_REQUIRED, "Recipient's mail content")
            ->addOption('attachment', "a", InputOption::VALUE_OPTIONAL, "Attachment's ID associate to sender");
    }
}