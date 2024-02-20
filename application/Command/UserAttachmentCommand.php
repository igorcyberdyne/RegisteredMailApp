<?php

namespace RegisteredMailApp\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mime\Part\DataPart;

class UserAttachmentCommand extends UserRegisteredMailCommand
{
    protected static $defaultName = 'app:user-attachment';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $idUser = $input->getOption("id");
        $filename = $input->getOption("filename");
        if (!file_exists($filename)) {
            $io->error("filename '$filename' provide not exist. You must provide the path");
            return Command::FAILURE;
        }

        try {
            $attachment = $this->userRegisteredMailService->addAttachment([
                'id_user' => $idUser,
                'file_name' => basename($filename),
                'file' => DataPart::fromPath($filename),
            ]);

            $attachment = [
                "id" => $attachment->getId(),
                "filesize" => $attachment->getData()["filesize"] . " octets",
            ];
            $io->writeln(["->" . json_encode($attachment)]);
            $io->success("User attachment created");

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
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'User ID ?')
            ->addOption('filename', null, InputOption::VALUE_REQUIRED, 'Path of file?');
    }


}