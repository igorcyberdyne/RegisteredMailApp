<?php

namespace RegisteredMailApp\Service;


use Exception;
use RegisteredMailApp\Entity\Attachment;
use RegisteredMailApp\Entity\RegisteredMail;
use RegisteredMailApp\Entity\User;
use RegisteredMailApp\Exception\AttachmentException;
use RegisteredMailApp\Exception\RegisteredMailException;
use RegisteredMailApp\Exception\ResourceNotFoundException;
use RegisteredMailApp\Exception\UserException;
use RegisteredMailApp\Gateway\AR24\Interfaces\AttachmentGatewayInterface;
use RegisteredMailApp\Gateway\AR24\Interfaces\RegisteredMailGatewayInterface;
use RegisteredMailApp\Gateway\AR24\Interfaces\UserGatewayInterface;
use RegisteredMailApp\Repository\Interfaces\AttachmentRepositoryInterface;
use RegisteredMailApp\Repository\Interfaces\RegisteredMailRepositoryInterface;
use RegisteredMailApp\Repository\Interfaces\UserRepositoryInterface;

class UserRegisteredMailService
{
    /** @var UserGatewayInterface */
    private $userGateway;
    private $userRepository;
    private $attachmentGateway;
    private $attachmentRepository;
    private $registeredMailRepository;

    private $registeredMailGateway;

    public function __construct(
        UserGatewayInterface              $userGateway,
        AttachmentGatewayInterface        $attachmentGateway,
        RegisteredMailGatewayInterface    $registeredMailGateway,
        UserRepositoryInterface           $userRepository,
        AttachmentRepositoryInterface     $attachmentRepository,
        RegisteredMailRepositoryInterface $registeredMailRepository
    )
    {
        $this->userGateway = $userGateway;
        $this->attachmentGateway = $attachmentGateway;
        $this->registeredMailGateway = $registeredMailGateway;
        $this->userRepository = $userRepository;
        $this->attachmentRepository = $attachmentRepository;
        $this->registeredMailRepository = $registeredMailRepository;
    }

    /**
     * @throws UserException
     * @throws ResourceNotFoundException
     */
    public function createUser(array $data): User
    {
        $user = $this->userGateway->createUser($data);

        return $this
            ->userRepository
            ->saveUser($user["id"], $user)
            ->findById($user["id"]);
    }

    /**
     * @throws ResourceNotFoundException
     */
    public function getUserById($id): User
    {
        /*return $this
            ->userRepository
            ->findById($id);*/

        try {
            return $this
                ->userRepository
                ->findById($id);
        } catch (ResourceNotFoundException $exception) {
            try {
                $user = $this->userGateway->getUser(["id_user" => $id]);

                // Refresh store
                return $this
                    ->userRepository
                    ->saveUser($user["id"], $user)
                    ->findById($user["id"]);
            } catch (Exception $ex) {
                throw $exception;
            }
        }
    }


    /**
     * @return User[]|array
     */
    public function getUsers(): array
    {
        return $this
            ->userRepository
            ->findAll();
    }


    /**
     * @throws AttachmentException
     * @throws ResourceNotFoundException
     */
    public function addAttachment(array $data): Attachment
    {
        $attachment = $this->attachmentGateway->addAttachment($data);

        return $this
            ->attachmentRepository
            ->saveAttachment($attachment["file_id"], $data["id_user"], $attachment)
            ->findById($attachment["file_id"]);
    }

    /**
     * @param array $data
     * @return RegisteredMail
     * @throws RegisteredMailException
     * @throws ResourceNotFoundException
     */
    public function sendMailTo(array $data): RegisteredMail
    {
        $mail = $this->registeredMailGateway->sendMail($data);

        return $this
            ->registeredMailRepository
            ->saveRegisteredMail($mail["id"], $data["id_user"], $mail)
            ->findById($mail["id"]);
    }

    /**
     * @param $id
     * @return RegisteredMail
     * @throws ResourceNotFoundException
     */
    public function getMailInfo($id): RegisteredMail
    {
        /*return $this
            ->registeredMailRepository
            ->findById($id);*/

        try {
            return $this
                ->registeredMailRepository
                ->findById($id);
        } catch (ResourceNotFoundException $exception) {
            try {
                $mail = $this->registeredMailGateway->getRegisteredMail(["id" => $id]);

                return $this
                    ->registeredMailRepository
                    ->saveRegisteredMail($mail["id"], $mail["id_sender"], $mail)
                    ->findById($mail["id"]);
            } catch (Exception $ex) {
                throw $exception;
            }
        }
    }

}