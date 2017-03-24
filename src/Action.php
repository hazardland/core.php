<?php

    namespace Core;

    class Action
    {
        private $name;
        private $path; //make later private
        private $callback;
        private $method;
        private $options;
        private $inputs = []; //make later private
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
        public function where ($name, $type)
        {
            $this->reset ();
            $this->where[$name] = $type;
            return $this;
        }
        public function name ($name)
        {
            $this->name = $name;
            Route::name ($name, $this);
            return $this;
        }
        public function getPath()
        {
            return $this->path;
        }
        private function reset ()
        {
            $this->inputs = [];
            $this->pattern = null;
        }
        private function getPattern ()
        {
            if ($this->pattern===null)
            {
                //first escape regex chars
                $pattern = preg_quote ($this->path,'/');

                //find variables in routes
                $matches = [];
                preg_match_all('/(\\\\\{[a-zA-Z0-9_]+\\\\\})+/', $pattern, $matches);

                if (isset($matches[0]) && count($matches[0]))
                {
                    foreach ($matches[0] as $match)
                    {
                        $name = substr($match,2,-2);
                        $input = new Input ($name,(isset($this->where[$name])?$this->where[$name]:0));
                        //debug ($name,$this->path);
                        $pattern = str_replace ($match,$input->getPattern(),$pattern);
                        $this->inputs[] = $input;
                    }
                }
                //debug ($matches, $this->path);
                $this->pattern = '/^'.$pattern.'$/';
            }
            return $this->pattern;
        }
        public function isActive (Request $request)
        {
            //debug ($this->method);
            //debug($request->getMethod());
            //debug ($this->getPattern(), $this->path);
            if ($this->method!==null && $this->method!=$request->getMethod())
            {
                return false;
            }
            if ($request->getPath()==='/' && $this->path==='/')
            {
                return true;
            }
            $result = preg_match($this->getPattern(), $request->getPath());
            if ($result===1)
            {
                return true;
            }
            return false;
        }
        public function getInput ($path)
        {
            if ($path==='/' && $this->path==='/')
            {
                return [];
            }
            $matches = [];
            preg_match_all($this->getPattern(), $path, $matches, PREG_SET_ORDER);
            if (!isset($matches[0]) || count($matches[0])<=1) return [];
            $result = [];
            foreach ($matches[0] as $key=>$value)
            {
                if ($key==0) continue;
                $result[$this->inputs[$key-1]->getName()] = $value;
            }
            return $result;
        }
        public function fillPath ($args)
        {
            $this->getPattern();
            $result = $this->path;
            if ($this->inputs)
            {
                //debug ($args);
                foreach ($this->inputs as $input)
                {
                    $result = str_replace ('{'.$input->getName().'}',$args[$input->getName()],$result);
                }
            }
            return $result;
        }
        public function execute (Request $request)
        {
            if (is_object($this->callback))
            {
                call_user_func_array ($this->callback, $this->getInput($request->getPath()));
            }
        }

    }
