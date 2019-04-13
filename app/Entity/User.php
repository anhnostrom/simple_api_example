<?php

namespace Entity;

class User
{
    const FIELD_ID = 0;
    const FIELD_NAME = 1;
    const FIELD_CREATED_AT = 2;
    const FIELD_UPDATED_AT = 3;

    private $id;
    private $name;
    private $created_at;
    private $updated_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function serializeToArray(): array
    {
        return [
            self::FIELD_ID => $this->id,
            self::FIELD_NAME => $this->name,
            self::FIELD_CREATED_AT => $this->created_at,
            self::FIELD_UPDATED_AT => $this->updated_at,
        ];
    }

    public function fillData(array $data): self
    {
        $this->id = $data[self::FIELD_ID] ?? null;
        $this->name = $data[self::FIELD_NAME] ?? null;
        $this->created_at = $data[self::FIELD_CREATED_AT] ?? null;
        $this->updated_at = $data[self::FIELD_UPDATED_AT] ?? null;

        return $this;
    }

}