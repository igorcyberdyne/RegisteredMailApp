<?php

namespace RegisteredMailApp\Command;

use RegisteredMailApp\Service\UserRegisteredMailService;
use Symfony\Component\Console\Command\Command;

abstract class UserRegisteredMailCommand extends Command
{
    protected $userRegisteredMailService;

    public function __construct(
        UserRegisteredMailService $userRegisteredMailService
    )
    {
        parent::__construct();

        $this->userRegisteredMailService = $userRegisteredMailService;
    }

}