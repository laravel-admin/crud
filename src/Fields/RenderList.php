<?php

namespace LaravelAdmin\Crud\Fields;

/**
 * Class to Render all fields for a list view in a CRUD model
 */
class RenderList
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
	 * Generate the labels for the head of the table
	 * @return \Illuminate\Support\Collection
     */
	public function headings()
	{
		//	Map all fields and return the label
		return $this->fields->map(function($item)
		{
			return $item['label'] ?? '-';
		});
	}

	/**
	 * Return all values of a model instance
	 * @param  Model $model
	 * @return \Illuminate\Support\Collection
     */
	public function values($model)
	{
		//	Map over every item in fields and return the value of the specified field
		return $this->fields->map(function($item) use ($model)
		{
			return $this->render($model, $item);
		});
	}

	/**
	 * Render a field of an instance
	 * @param  Model $model
	 * @param  array $field
	 * @return string
	 */
	public function render($model, $field)
	{
		//	Is the formatter defined for the field
		if (!isset($field['formatter'])) return null;

		//	Is the formatter a string
		if (is_string($field['formatter']))
		{
			$prop =  $field['formatter'];
			return $model->$prop;
		}

		//	Is the formatter a closure?
		if (is_callable($field['formatter']))
		{
			return $field['formatter']($model);
		}
	}
}
