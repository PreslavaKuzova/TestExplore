<?php

require_once 'controllers/BaseController.php';

class Chat extends BaseController
{

    public function __construct()
    {
        parent::__construct('views/chat.phtml');
        $this->view->username = $this->name;
    }

    public function index()
    {
        $this->render();

    }

    public function messageSent()
    {
        $messages_buffer_file = 'messages.json';
        // Number of most recent messages kept in the buffer
        $messages_buffer_size = 1000;

        if (isset($_POST['content']) and isset($_POST['name'])) {
            // Open, lock and read the message buffer file
            $buffer = fopen($messages_buffer_file, 'r+b');
            flock($buffer, LOCK_EX);
            $buffer_data = stream_get_contents($buffer);

            // Append new message to the buffer data or start with a message id of 0 if the buffer is empty
            $messages = $buffer_data ? json_decode($buffer_data, true) : array();
            $next_id = (count($messages) > 0) ? $messages[count($messages) - 1]['id'] + 1 : 0;
            $messages[] = array('id' => $next_id, 'time' => time(), 'name' => $_POST['name'], 'content' => $_POST['content']);

            // Remove old messages if necessary to keep the buffer size
            if (count($messages) > $messages_buffer_size)
                $messages = array_slice($messages, count($messages) - $messages_buffer_size);

            // Rewrite and unlock the message file
            ftruncate($buffer, 0);
            rewind($buffer);
            fwrite($buffer, json_encode($messages));
            flock($buffer, LOCK_UN);
            fclose($buffer);

            exit();
        }
    }
}