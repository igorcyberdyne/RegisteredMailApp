<?php

namespace RegisteredMailApp\Repository\Interfaces;

use RegisteredMailApp\Entity\Attachment;
use RegisteredMailApp\Exception\ResourceNotFoundException;

interface AttachmentRepositoryInterface
{
    /**
    * @throws ResourceNotFoundException
    */
    public function findById($id): Attachment;

    /**
     * @return Attachment[]|array
     */
    public function findAllByUserId($idUser): array;

    public function saveAttachment(
        $id,
        $idUser,
        array $data
    ): AttachmentRepositoryInterface;
}