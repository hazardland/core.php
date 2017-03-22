<?php

    namespace Core;

    class Action
    {
        public $path; //make later private
        private $callback;
        private $method;
        private $options;
        public $inputs = []; //make later private
        private $where = [];
        private $pattern;
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
            $this->reset ();
            $this->where[$name] = $type;
            return $this;
        }
        private function reset ()
        {
            $this->inputs = [];
            $this->pattern = null;
        }
        private function pattern ()
        {
            if ($this->pattern===null)
            {
                //first escape regex chars
                $pattern = preg_quote ($this->path,'/');

                //find variables in routes
                $matches = [];
                preg_match_all('/(\\\\\{[a-zA-Z0-9]+\\\\\})+/', $pattern, $matches);

                if (isset($matches[0]) && count($matches[0]))
                {
                    foreach ($matches[0] as $match)
                    {
                        $name = substr($match,2,-2);
                        $input = new Input ($name,(isset($this->where[$name])?$this->where[$name]:0));
                        //debug ($name,$this->path);
                        $pattern = str_replace ($match,$input->pattern(),$pattern);
                        $this->inputs[] = $input;
                    }
                }
                //debug ($matches, $this->path);
                $this->pattern = '/^'.$pattern.'$/';
            }
            return $this->pattern;
        }
        public function match ($route)
        {
            if ($route==='/' && $this->path==='/')
            {
                return true;
            }
            $result = preg_match($this->pattern(), $route);
            if ($result===1)
            {
                return true;
            }
            return false;
        }
        public function input ($route)
        {
            if ($route==='/' && $this->path==='/')
            {
                return [];
            }
            $matches = [];
            preg_match_all($this->pattern(), $route, $matches, PREG_SET_ORDER);
            if (!isset($matches[0]) || count($matches[0])<=1) return [];
            $result = [];
            foreach ($matches[0] as $key=>$value)
            {
                if ($key==0) continue;
                $result[$this->inputs[$key-1]->name()] = $value;
            }
            return $result;
        }
        public function execute ($route)
        {

        }

    }
