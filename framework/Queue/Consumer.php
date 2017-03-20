<?php
namespace Framework\Queue;

use PMG\Queue\DefaultConsumer;

class Consumer extends DefaultConsumer
{
    /**
     * {@inheritdoc}
     */
    public function once($queueName)
    {
        $envelope = $this->getDriver()->dequeue($queueName);
        if (!$envelope) {
            return null;
        }

        $message = $envelope->unwrap();

        $this->getLogger()->debug('Handling message {msg}', ['msg' => $message->getName()]);
        $result = $this->handleMessage($message);
        $this->getLogger()->debug('Handled message {msg}', ['msg' => $message->getName()]);

        if ($result || $result === null) {
            $this->getDriver()->ack($queueName, $envelope);
            $this->getLogger()->debug('Acknowledged message {msg}', ['msg' => $message->getName()]);
        } else {
            $this->failed($queueName, $envelope);
            $this->getLogger()->debug('Failed message {msg}', ['msg' => $message->getName()]);
        }

        return $result;
    }
}
