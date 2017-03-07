<?php

    namespace App;

    class Entry
    {
        const GET = 1;
        const POST = 2;
        public $path;
        public $callback;
        public $method;
        public $where = [];
        public function __construct ($path, $callback, $method=null)
        {
            $this->path = $path;
            $this->callback = $callback;
            $this->method = $method;
        }
        public function isClosure()
        {
            return is_object($this->callback) && ($this->callback instanceof Closure);
        }
        public function where ($name, $type)
        {
            $this->where[$name] = $type;
            return $this;
        }
    }
