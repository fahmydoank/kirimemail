<?php

use Phalcon\Mvc\Model,
	Phalcon\Validation,
	Phalcon\Validation\Validator\Email as EmailValidator,
	Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Subscribes extends Model
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

	public function validation()
    {
        $validator = new Validation();
        
        $validator->add(
            'email',
            new EmailValidator([
            'message' => 'Invalid email given!'
        ]));
        $validator->add(
            'email',
            new UniquenessValidator([
            'message' => 'Sorry, The email was registered'
        ]));
        
        return $this->validate($validator);
    }
}
