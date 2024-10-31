<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ClientSearchDto
{
    public string $telephone;
    public string $surname;

    public function __construct(){
        $this->telephone = '';
        $this->surname = '';
    }
}