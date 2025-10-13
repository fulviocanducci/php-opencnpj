<?php

namespace Canducci\OpenCnpj\Models;

use JsonSerializable;

abstract class BaseModel implements JsonSerializable
{
    /**
     * Converte o objeto em array recursivamente
     */
    public function toArray(): array
    {
        $data = get_object_vars($this);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = array_map(fn($item) => 
                    $item instanceof BaseModel ? $item->toArray() : $item,
                    $value
                );
            } elseif ($value instanceof BaseModel) {
                $data[$key] = $value->toArray();
            }
        }

        return $data;
    }

    /**
     * Implementação do JsonSerializable
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
