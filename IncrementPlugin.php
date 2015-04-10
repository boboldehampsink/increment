<?php

namespace Craft;

class IncrementPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Increment');
    }

    public function getVersion()
    {
        return '0.2';
    }

    public function getDeveloper()
    {
        return 'Bob Olde Hampsink';
    }

    public function getDeveloperUrl()
    {
        return 'http://www.itmundi.nl';
    }

    public function init()
    {
        // Check increment fields to make sure no duplicate values are inserted
        craft()->on('elements.beforeSaveElement', function (Event $event) {

            // Check if this is a new element
            $isNewElement = $event->params['isNewElement'];

            // Get element
            $element = $event->params['element'];

            // Exisiting elements don't change, so no need to check
            if ($isNewElement) {

                // Get element fields
                $fields = $element->getFieldLayout()->getFields();

                // Loop through element fields
                foreach ($fields as $field) {

                    // Get field model
                    $field = $field->getField();

                    // Check if this field is an Increment field
                    if ($field->type == $this->getClassHandle()) {

                        // Re-calculate max value
                        $max = craft()->db->createCommand()->select('MAX(`field_'.$field->handle.'`)')->from('content')->queryScalar();

                        // Get current value
                        $currentValue = $element->{$field->handle};

                        // Current value should by higher than max, otherwise a duplicate element could be created
                        if ($currentValue <= $max) {
                            $element->setContentFromPost(array($field->handle => ($max + 1)));
                        }
                    }
                }
            }
        });
    }
}
