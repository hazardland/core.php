<?php

    namespace Core;

    class Input
    {
        const BOOL = 1;
        const BOOLEAN = 1;
        const INT = 2;
        const INTEGER = 2;
        const FLOAT = 3;
        const STRING = 3;
        const DATE = 4;
        const TIME = 5;
        const DATETIME = 6;
        const EMAIL = 7;
        public $name; //make later private
        public $type; //make later private
        public function __construct($name, $type)
        {
            $this->name = $name;
            $this->type = $type;
        }
        public function pattern()
        {
            if ($this->type==Input::INTEGER)
            {
                return '([0-9]+)';
            }
            if ($this->type==Input::FLOAT)
            {
                return '([0-9\.]+)';
            }
            if ($this->type==Input::BOOLEAN)
            {
                return '(0|false|False|FALSE|1|true|True|TRUE)';
            }
            if ($this->type==Input::EMAIL)
            {
                return '([^\.][a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-.]+[^\.])';
            }
            return '([a-zA-Z0-9@\-\.\;\,]+)';
        }
        public function name()
        {
            return $this->name;
        }
        public function type()
        {
            return $this->type;
        }
    }
