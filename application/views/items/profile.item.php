<?php

class ProfileItem {
    public function __construct(String $type,String $name,?int $posX,?int $posY) {
        $mapTyps = [
            'NAME' =>'TextInput',
            'PASSWORD' =>'TextInput',
            'EMAIL'=>'TextInput',
            'TEXT' =>'TextInput',
            'PHOTO'=>'Photo',
            'PHONE'=>'TextInput'
        ];
        $this->basicType = $mapTyps[$type];
        $this->type = $type;
        $this->name = $name;       
        $this->posX = $posX;       
        $this->posY = $posY;       
    }
} 