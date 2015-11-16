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
class IncrementService extends BaseApplicationComponent
{

    /**
     * @param $fieldHandle
     * @return mixed
     */
    public function getNewIncrement($fieldHandle, $minValue)
    {
        $maxValue = craft()->db->createCommand()->select('MAX(`field_' . $fieldHandle . '`)')->from('content')->queryScalar();

        return $minValue > $maxValue ? $minValue : ($maxValue + 1);
    }

    /**
     * @param EntryModel $entry
     */
    public function recalculateIncrements(EntryModel $entry)
    {
        foreach ($entry->getFieldLayout()->getFields() as $field) {
            $field = $field->getField();

            /** if increment and recalculate, set new increment */
            if ($field->type == 'Increment' && $field->settings['recalculate']) {
                $this->recalculateIncrement($entry, $field);
            }
        }
    }

    /**
     * @param EntryModel $entry
     * @param $field
     */
    public function recalculateIncrement(EntryModel $entry, FieldModel $field)
    {
        /** @var IncrementFieldType $fieldType */
        $fieldType = $field->getFieldType();

        $fieldHandle = $field->handle;
        $increment = $fieldType->getIncrementNumber($entry->$fieldHandle);
        $newIncrement = craft()->increment->getNewIncrement($fieldHandle, $increment);

        $entry->setContent(array($fieldHandle => $newIncrement));
    }
}
