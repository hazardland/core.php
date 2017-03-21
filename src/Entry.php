<?php

    namespace Core;

    class Entry
    {
        public $path;
        public $callback;
        public $method;
        public $options;
        public $where = [];
        public function __construct ($path, $callback, $method=null, $options=[])
        {
            if (is_array($options))
            {
                if (isset($options['prefix']) && $path!='/')
                {
                    $path = $options['prefix'].'/'.$path;
                }
                $this->options = $options;
            }
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
        public function getRegex ()
        {
            return '/^'.$this->path.'$/';
        }
    }
