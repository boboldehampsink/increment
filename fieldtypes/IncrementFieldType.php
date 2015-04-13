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
            'padding'   => AttributeType::Number,
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

        // If value is not yet set
        if (empty($value)) {

            // Get current max number
            $value = $this->_getMaxNumber($settings->increment);
        } else {

            // Save value without prefix
            $value = str_replace(craft()->templates->renderObjectTemplate($settings->prefix, $this->element), '', $value);

            // Re-calculate max number
            $value = $this->_getMaxNumber($value);
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

            // Craft sets postDate for Live Preview. We do the same.
            $this->element->postDate = new DateTime();

            // Get current max number
            $value = $this->_getMaxNumber($settings->increment);
        }

        // Pad zeroes
        $value = str_pad($value, $settings->padding, '0', STR_PAD_LEFT);

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

    /**
     * Get current max number from db.
     *
     * @return string
     */
    private function _getMaxNumber($value)
    {
        // Get current max number from db
        $max = craft()->db->createCommand()->select('MAX(`field_'.$this->model->handle.'`)')->from('content')->queryScalar();

        // Check if this is valid or up one
        return $value > $max ? $value : ($max+1);
    }
}
