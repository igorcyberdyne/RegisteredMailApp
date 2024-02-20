<?php

namespace RegisteredMailApp\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends UserRegisteredMailCommand
{
    protected static $defaultName = 'app:create-user';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userData = $this->givenUserData();

        $io = new SymfonyStyle($input, $output);

        try {
            $user = $this->userRegisteredMailService->createUser($userData);

            $user = [
                "id" => $user->getId(),
                "firstname" => $user->getData()["firstname"],
                "lastname" => $user->getData()["lastname"],
            ];
            $io->writeln(["-> " . json_encode($user)]);
            $io->success("User created ");

            return Command::SUCCESS;
        } catch (Exception $exception) {
            $io->error($exception->getMessage());
        }
        return Command::FAILURE;
    }


    protected function configure(): void
    {
        $this
            ->setDescription("Permet de créer un utilisateur")
            ->setHelp("{$this->getDescription()}");
    }


    private function givenUserData(
        ?string $email = null,
        ?string $firstname = null,
        ?string $lastname = null
    ): array
    {
        $uniqid = uniqid();
        return [
            'email' => $email ?? "ig.gamath+" . $uniqid . "@gmail.com",
            'firstname' => $lastname ?? 'Command firstname',
            'lastname' => $firstname ?? 'Command name',
            'country' => 'FR',
            'address1' => '1 rue de la république',
            'statut' => 'particulier',
            'city' => 'Paris',
            'zipcode' => '75000',
            'gender' => 'F',
            'password' => 'yqxEJfvs4Rx5S9T',
            'company_siret' => rand(123456, 999999),
            'company_tva' => '',
            'address2' => 'Batiment B',
            'confirmed' => '1',
            'cgu' => '1',
            'billing_email' => str_replace("+", "+facture-", $email),
            'notify_ev' => '1',
            'notify_ar' => '1',
            'notify_ng' => '1',
            'notify_consent' => '1',
            'notify_eidas_to_valid' => '1',
            'notify_recipient_update' => '1',
            'notify_waiting_ar_answer' => '1',
            'is_legal_entity' => '0'
        ];
    }

}