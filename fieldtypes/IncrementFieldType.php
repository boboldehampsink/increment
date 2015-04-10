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
            'prefix'    => AttributeType::String,
            'increment' => AttributeType::Number,
        );
    }

    // Set settings html
    public function getSettingsHtml()
    {
        return craft()->templates->render('increment/_settings', array(
            'settings' => $this->getSettings(),
        ));
    }

    /**
     * @inheritDoc IFieldType::prepValueFromPost()
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function prepValueFromPost($value)
    {
        // Get settings
        $settings = $this->getSettings();

        // Save value without prefix
        $value = str_replace(craft()->templates->renderObjectTemplate($settings->prefix, $this->element), '', $value);

        // Return value
        return $value;
    }

    // Prep value for output
    public function prepValue($value)
    {
        // Get settings
        $settings = $this->getSettings();

        // If value is not yet set
        if (!isset($value)) {

            // Get current field handle
            $handle = $this->model->handle;

            // Get current max number
            $max = craft()->db->createCommand()->select('MAX(`field_'.$handle.'`)')->from('content')->queryScalar();

            // Get increment number
            $increment = $settings->increment;

            // Determine next number
            $value = $increment > $max ? $increment : ($max+1);

            // Add prefix
            $value = craft()->templates->renderObjectTemplate($settings->prefix, $this->element).$value;
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
            'value' => $value,
        ));
    }
}
