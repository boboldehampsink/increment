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
            'recalculate' => array(AttributeType::Bool, 'default' => true),
            'prefix'      => AttributeType::String,
            'increment'   => AttributeType::Number,
            'padding'     => AttributeType::Number,
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
        $this->setPostDate();

        $value = empty($value) ? $this->_getMaxNumber() : $this->getIncrementNumber($value);

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
        $this->setPostDate();

        // If value is not yet set
        if (!isset($value)) {
            $value = $this->_getMaxNumber();
        }

        // Get settings
        $settings = $this->getSettings();

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
     * Get value without prefix
     * @param string $value
     * @return int
     */
    public function getIncrementNumber($value)
    {
        $settings = $this->getSettings();
        return (int)str_replace(craft()->templates->renderObjectTemplate($settings->prefix, $this->element), '', $value);
    }

    /**
     * Get current max number from db.
     *
     * @return string
     */
    private function _getMaxNumber()
    {
        $settings = $this->getSettings();
        return craft()->increment->getNewIncrement($this->model->handle, $settings->increment);
    }

    /**
     * Craft sets postDate for Live Preview. We do the same.
     */
    private function setPostDate()
    {
        if (is_null($this->element->postDate)) {
            $this->element->postDate = new DateTime();
        }
    }
}
