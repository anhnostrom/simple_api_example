<?php

namespace Entity;

use DateTime;
use JsonSerializable;

class Post implements JsonSerializable
{
    const FIELD_ID = 0;
    const FIELD_USER_ID = 1;
    const FIELD_USER_NAME = 2;
    const FIELD_TEXT = 3;
    const FIELD_CREATED_AT = 4;
    const FIELD_UPDATED_AT = 5;
    const FIELD_IS_DELETED = 6;

    private $id;
    private $user_id;
    private $user_name;
    private $text;
    private $created_at;
    private $updated_at;
    private $is_deleted;

    private $createdAtDateTime;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function serializeToArray(): array
    {
        return [
            self::FIELD_ID => $this->id,
            self::FIELD_USER_ID => $this->user_id,
            self::FIELD_USER_NAME => $this->user_name,
            self::FIELD_TEXT => $this->text,
            self::FIELD_CREATED_AT => $this->created_at,
            self::FIELD_UPDATED_AT => $this->updated_at,
            self::FIELD_IS_DELETED => $this->is_deleted,
        ];
    }

    public function fillData(array $data): self
    {
        $this->id = $data[self::FIELD_ID] ?? null;
        $this->user_id = $data[self::FIELD_USER_ID] ?? null;
        $this->user_name = $data[self::FIELD_USER_NAME] ?? null;
        $this->text = $data[self::FIELD_TEXT] ?? null;
        $this->created_at = $data[self::FIELD_CREATED_AT] ?? null;
        $this->updated_at = $data[self::FIELD_UPDATED_AT] ?? null;
        $this->is_deleted = $data[self::FIELD_IS_DELETED] ?? null;

        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'id' => (int)$this->id,
            'user_id' => (int)$this->user_id,
            'user_name' => (string)$this->user_name,
            'text' => (string)$this->text,
            'created_at' => $this->getCreatedAt()->format('c'),
        ];
    }

    public function getCreatedAt(): DateTime
    {
        if (null === $this->createdAtDateTime) {
            $this->createdAtDateTime = new DateTime($this->created_at);
        }

        return $this->createdAtDateTime;
    }

}