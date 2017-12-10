<?php

class base_thread
{
    protected $state;

    public function __construct($state)
    {
        $this->state = $state;
    }
    public function __call($name, $args)
    {
        $this->text = $args[1];
        if ($name == 'run') {
            call_user_func_array([$this, $name], $args);
        }
    }
    private function run()
    {
        return false;
    }
}
