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
        return '0.1';
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
        // check increment fields to make sure no duplicate values are inserted
        craft()->on('entries.beforeSaveEntry', function(Event $event) use ($outer) {
            $isNewEntry = $event->params['isNewEntry'];
            $entry = $event->params['entry'];

            // exisiting entries don't change, so no need to check
            if ($isNewEntry) {
                $fields = $entry->getFieldLayout()->getFields();
                foreach ($fields as $field) {
                    if ($field->getField()->type == 'Increment') {
                        $handle = $field->getField()->handle;
                        $max = craft()->db->createCommand()->select('MAX(`field_' . $handle . '`)')->from('content')->queryScalar();
                        $currentValue = $entry[$handle];

                        // current value should by higher than max, otherwise a duplicate entry could be created
                        if ($currentValue <= $max) {
                            $entry->setContentFromPost(array($handle => $max + 1));
                        }
                    }
                }
            }
        });
    }
}
