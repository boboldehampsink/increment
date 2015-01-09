<?php   
namespace Craft;

class IncrementFieldType extends BaseFieldType
{
	
	// Increment name
	public function getName()
	{
		return Craft::t('Increment');
	}

	// Define field as number
	public function defineContentAttribute()
	{
		return AttributeType::Number;
	}

	// Settings
	protected function defineSettings()
	{
		return array(
			'increment' => AttributeType::Number,
		);
	}

	// Set settings html
	public function getSettingsHtml()
	{
		return craft()->templates->render('increment/_settings', array(
			'settings' => $this->getSettings()
		));
	}

	// Prep value for output
	public function prepValue($value)
	{

		// If value is not yet set
		if(!isset($value)) {

			// Get current field handle
			$handle = $this->model->handle;
			
			// Get current max number
			$max = craft()->db->createCommand()->select('MAX(`field_' . $handle . '`)')->from('content')->queryScalar();

			// Get increment number
			$increment = $this->getSettings()->increment;

			// Determine next number
			$value = $increment > $max ? $increment : ($max+1);
			
		}

		// Return this value
		return $value;

	}

	// Set input html
	public function getInputHtml($name, $value)
	{
		
		// Return html
		return craft()->templates->render('increment/_input', array(
			'name'  => $name,
			'value' => $value
		));

	}

}