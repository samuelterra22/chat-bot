<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 04/03/18
 * Time: 23:13
 */

namespace ChatBot\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;
use ChatBot\Student;

class InscriptionConversation extends Conversation
{

    protected $student;

    public function welcomeStudent()
    {
        // get user instance
        $user = $this->bot->getUser();

        // set student name
        $this->student->name = $user->getFirstName() . " " . $user->getLastName();

        // save the model with the user instance
        $this->student->save();

        // display welcome message
        $this->say("Olá {$this->student->name}, obrigado pelo interesse no workshop de ChatBots!");

        // ask for email address
        $this->askEmailAddress();
    }

    public function askEmailAddress()
    {

    }

    /**
     * @return mixed
     */
    public function run()
    {

        // start a new model instance
        $this->student = new Student();

        // redirect to welcome
        $this->welcomeStudent();
    }
}