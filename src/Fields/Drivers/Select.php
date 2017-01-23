<?php

namespace LaravelAdmin\Crud\Fields\Drivers;

use LaravelAdmin\Crud\Fields\Driver;

/**
 * Driver for a selectbox field
 */
class Select extends Driver
{

	/**
	 * Get the available options for the selectbox
	 * @param  Model $model
	 * @return array
	 */
	public function options($model)
	{
		//	If the options attribute is a callback
		if (isset($this->config['options']) && is_callable($this->config['options']))
		{
			return $this->config['options']($model);
		}

		//	TODO: Create more opportunities then only a callback

		return [];
	}
}
