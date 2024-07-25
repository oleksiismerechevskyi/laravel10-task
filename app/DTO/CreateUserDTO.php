<?php

namespace App\DTO;

class CreateUserDTO {
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly int $position_id,
        public readonly string $photo,
      ) {}
      
      public function toArray() {
        return call_user_func('get_object_vars', $this);
    }
}