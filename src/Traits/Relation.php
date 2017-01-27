<?php

namespace LaravelAdmin\Crud\Traits;

trait Relation
{
	/**
	 * Get the model based on the class wich is defined in the controller
	 * An example of a call is $this->model('find', 1) is the same as User::find(1)
	 * @return Builder
	 */

	protected function relation()
	{
		//	Validate the given model name
		$this->validateRelation();

		//	Get all arguments given to this method
		$args 	= 	func_get_args();

		//	The first argument is the method name, who will be called statically
		$method = 	array_shift($args);

		if (!$method) return new $this->relation;

		//	Execute the class method with the given arguments
		return call_user_func_array($this->relation . '::' . $method, $args);
	}

	/**
	 * Validate the model name which is specified in the controller
	 * @return void
	 */

	protected function validateRelation()
	{
		//	Check if the modelname is defined
		//	If not, throw an exception
		if (empty($this->relation))
			throw new \RuntimeException('No relation defined in CRUD controller');

		//	Check if the class exists
		//	If not, throw an exception
		if (!class_exists($this->relation))
			throw new \RuntimeException('Model with name '.$this->relation.' not found');

		//	TODO: Check if the class is a valid Eloquent Model
	}
}
