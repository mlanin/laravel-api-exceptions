<?php

namespace Lanin\Laravel\ApiExceptions\Support;

class MessageBag extends \Illuminate\Support\MessageBag
{
    /**
     * Format an array of messages.
     *
     * @param  array   $messages
     * @param  string  $format
     * @param  string  $messageKey
     * @return array
     */
    protected function transform($messages, $format, $messageKey)
    {
        $messages = (array) $messages;

        // We will simply spin through the given messages and transform each one
        // replacing the :message place holder with the real message allowing
        // the messages to be easily formatted to each developer's desires.
        $replace = [':message', ':key'];

        foreach ($messages as &$message) {
            if (is_string($message)) {
                $message = str_replace($replace, [$message, $messageKey], $format);
            }
        }

        return $messages;
    }
}
