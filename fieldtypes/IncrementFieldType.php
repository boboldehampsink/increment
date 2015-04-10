<?php

namespace Craft;

/**
 * Increment Field Type.
 *
 * Number field with automatic incrementing.
 *
 * @author    Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 * @copyright Copyright (c) 2015, Bob Olde Hampsink
 * @license   MIT
 *
 * @link      http://github.com/boboldehampsink
 */
class IncrementFieldType extends BaseFieldType
{
    /**
     * Get fieldtype name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t('Increment');
    }

    /**
     * Define fieldtype as number.
     *
     * @return string
     */
    public function defineContentAttribute()
    {
        return AttributeType::Number;
    }

    /**
     * Define fieldtype settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'prefix'    => AttributeType::String,
            'increment' => AttributeType::Number,
        );
    }

    /**
     * Render settings template.
     *
     * @return string
     */
    public function getSettingsHtml()
    {
        return craft()->templates->render('increment/_settings', array(
            'settings' => $this->getSettings(),
        ));
    }

    /**
     * Prepares value after posting, removing the prefix and checking if its still unique.
     *
     * @param string $value
     *
     * @return string
     */
    public function prepValueFromPost($value)
    {
        // Get settings
        $settings = $this->getSettings();

        // Save value without prefix
        $value = str_replace(craft()->templates->renderObjectTemplate($settings->prefix, $this->element), '', $value);

        // Re-calculate max number
        $max = craft()->db->createCommand()->select('MAX(`field_'.$this->model->handle.'`)')->from('content')->queryScalar();

        // Current value should by higher than max, otherwise a duplicate element could be created
        if ($value <= $max) {
            $value = $max + 1;
        }

        // Return value
        return $value;
    }

    /**
     * Prepares value for output, adding the prefix.
     *
     * @param string $value
     *
     * @return string
     */
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
        }

        // Add prefix
        $value = craft()->templates->renderObjectTemplate($settings->prefix, $this->element).$value;

        // Return this value
        return $value;
    }

    /**
     * Render input template.
     *
     * @param string $name
     * @param string $value
     *
     * @return string
     */
    public function getInputHtml($name, $value)
    {
        return craft()->templates->render('increment/_input', array(
            'name'  => $name,
            'value' => $value,
        ));
    }
}
