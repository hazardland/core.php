<?php

    namespace Core;

    class Request
    {
        private $method;
        private $query;
        private $path;
        private $locale;
        public function __construct($query, $method=Method::GET)
        {
            $this->query = $query;
            if ($query!==null)
            {
                if (strpos($query,'/')!==false)
                {
                    $locale = substr($query, 0, strpos($query,'/'));
                    if (App::isLocale($locale))
                    {
                        App::setLocale($locale);
                        $query = substr($query,strpos($query,'/')+1);
                    }
                }
                else
                {
                    $locale = $query;
                    if (App::isLocale($locale))
                    {
                        App::setLocale($locale);
                        $query = '/';
                    }
                }
            }
            if (!isset($locale))
            {
                $locale = App::getLocale();
            }
            if ($query===null || $query=='')
            {
                $query = '/';
            }
            $this->path = $query;
            $this->locale = $locale;
            $this->method = $method;
        }
        public function getQuery ()
        {
            return $this->query;
        }
        public function getLocale()
        {
            return $this->locale;
        }
        public function getPath()
        {
            return $this->path;
        }
        public function getMethod()
        {
            return $this->method;
        }
    }
