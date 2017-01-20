<?php

namespace LaravelAdmin\Crud\Fields;

use Illuminate\Support\Facades\View;

/**
 * Render the fields of a CRUD model for the create and edit form
 */
class RenderDetail
{
	protected $fields;

	/**
	 * Send an array with the config of all fields to show
	 * @param array $fields
	 */
	public function __construct(array $fields)
	{
		$this->fields = collect($fields);
	}

	/**
	 * Return all values of a model instance
	 * @param  Model $model
	 * @return Collection
	 */
	public function values()
	{
		//	Map over every field and return an instance of the field driver for each item
		return $this->fields->map(function($item)
		{
			//	TODO: Does the driver attribute exists?
			$class = $item['driver'];

			//	TODO: Does the class exists?
			return new $class($item);
		});
	}
}
