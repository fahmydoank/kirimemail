<?php

class Subscribes extends \Phalcon\Mvc\Model
{

    /**
     * @var integer
     *
     */
    public $id;

    /**
     * @var string
     *
     */
    public $name;

    /**
     * @var string
     *
     */
    public $email;

    /**
     * @var string
     *
     */
    public $status;

    /**
     * Initializer method for model.
     */
    public function initialize()
    {
        $this->hasMany("id", "name", "email", "status");
    }

}
