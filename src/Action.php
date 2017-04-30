<?php

    namespace Core;

    use \Core\Request;
    use \Core\Input;

    class Action
    {
        /**
         * Route action name / for so called named routes
         * @var string
         */
        private $name;
        /**
         * Route action path
         * @var string
         */
        private $path; //make later private
        /**
         * Closure (callback function) or
         * 'Controller@method' controller method pointer
         * All controllers are asumed to be under \App\Controller namespace
         * For example 'Home@index' actually reffers to class
         * \App\Controller\Home
         * App is apps namespace all unique app related code must be placed under \App
         * @var mixed
         */
        private $callback;
        /**
         * Input method type
         * Method::GET
         * Method::POST
         * Method::PUT
         * Method::DELETE
         * Method::PATCH
         * Method::OPTIONS
         * @var int
         */
        private $method;
        /**
         * Route action configuration
         * @var [type]
         */
        private $options;
        public $filters = [];
        /**
         * Input objects extracted from route action path
         * @var array
         */
        private $inputs = []; //make later private
        /**
         * User defined input type specifications
         * @var array
         */
        private $types = [];
        /**
         * Route pattern cache
         * @var string
         */
        private $pattern;
        /**
         * Create route action
         * Where path is query part without locale (locales are detected automatically)
         * Path examples:
         * 'home'
         * 'blog/article/{id}' - Which matches query paths like 'blog/article/17'
         * 'blog/article/{id}-{*}' - Which matches query paths like 'blog/article/17-cool-blog-post'
         * @param string $path Route path
         * @param mixed $callback Callback function or Controller@method
         * @param string $method Request method, default is any (Method::GET, Method::Post, etc)
         * @param array  $options Action options
         */
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
        /**
         * Set input type
         * @param  string $name Input parameter name in route
         * @param  int $type Input:: constant
         * @param  string $pattern Custom pattern
         * @return \Core\Action Action
         */
        public function input ($name, $type, $pattern=null)
        {
            $this->reset ();
            $this->types[$name] = $type;
            return $this;
        }
        /**
         * Set route action name
         * @param  string $name Route action name
         * @return \Core\Action Action
         */
        public function name ($name)
        {
            $this->name = $name;
            Route::name ($name, $this);
            return $this;
        }
        /**
         * Filter requests to this route
         * @param  string $filter  [description]
         * @param  array  $options [description]
         * @return [type]          [description]
         */
        public function filter ($filter, array $options=[])
        {
            $this->filters[$filter] = $options;
            return $this;
        }
        /**
         * Get route path
         * @return string Path
         */
        public function getPath()
        {
            return $this->path;
        }
        /**
         * Reset pregenerated cache
         */
        private function reset ()
        {
            $this->inputs = [];
            $this->pattern = null;
        }
        /**
         * Build, cache and return path regex pattern
         * This method will cache pattern for farther use
         * Changing action parameters will cause pattern cache reset
         */
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
                        $input = new Input ($name,(isset($this->types[$name])?$this->types[$name]:0));
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
        /**
         * Chack if action is active against request
         * @param  \Core\Request $request Request to check against with
         * @return boolean result
         */
        public function isActive (Request $request)
        {
            //debug ($this->method);
            //debug($request->getMethod());
            //debug ($this->getPattern(), $this->path);
            if ($this->method!==null && $this->method!=$request->getMethod())
            {
                return false;
            }

            if (
                ($request->getPath()==='/' && $this->path==='/') ||
                preg_match($this->getPattern(), $request->getPath())===1
               )
            {
                //Maybe filter compiration must be before regex match?
                //If it will be cost effective
                if (is_array($this->filters) && count($this->filters))
                {
                    foreach ($this->filters as $name => $options)
                    {
                        $filter = Route::getFilter($name);
                        if (!$filter($this, $request, $options))
                        {
                            return false;
                        }
                    }
                }
                return true;
            }
            return false;
        }
        /**
         * Extract input from query path
         * From http://sample.com/en/blog/article/17 => blog/article/17 is query path
         * @param  string $path Query path
         * @return array Path input array route_input_name=>value
         */
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
        /**
         * Fill action path with values
         * Action path actually looks like that:
         * /blog/article/{id}
         * This method use used to generation action url path part
         * @param  array $args Arguments (ex. ['id'=>17])
         * @return string Result
         */
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
        /**
         * Execute action with request
         * @param  \Core\Request $request Request to extract input from
         * @return mixed Execute result
         */
        public function execute (Request $request)
        {
            $result = null;
            if (is_object($this->callback))
            {
                $result = call_user_func_array ($this->callback, $this->getInput($request->getPath()));
            }
            else
            {
                $separator = strpos($this->callback, '@');
                if ($separator!==false)
                {
                    $controller = substr ($this->callback, 0, $separator);
                    $method = substr ($this->callback, $separator+1);
                    if ($controller!==false)
                    {
                        $class = '\\App\\Controller\\'.$controller;
                        if ($method===false)
                        {
                            $method = 'index';
                        }
                        //class_alias ($class,'Controller');
                        $object = new $class;
                        $result = call_user_func_array ([$object, $method], $this->getInput($request->getPath()));
                    }
                }
            }
            return $result;
        }

    }
