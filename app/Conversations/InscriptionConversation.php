<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 04/03/18
 * Time: 23:13
 */

namespace ChatBot\Conversations;


use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use ChatBot\integration\SearchZip;
use ChatBot\Student;

class InscriptionConversation extends Conversation
{

    protected $student;
    protected $address;

    public function welcomeStudent()
    {
        // get user instance
        $user = $this->bot->getUser();

        // set student name
        $this->student->name = $user->getFirstName() . " " . $user->getLastName();

        // save facebook id
        $this->student->profile_id = $user->getId();

        // save the model with the user instance
        $this->student->save();

        // display welcome message
        $this->say("Olá {$this->student->name}, obrigado pelo interesse no workshop de ChatBots!");

        // ask for email address
        $this->askEmailAddress();
    }

    public function askEmailAddress()
    {
        $message = "Para que eu possa registrar sua inscrição, vou precisar do seu email, poderia me informar?";

        if ($this->student->email) {
            $this->say("Já tenho seu email: {$this->student->email}!");
            // pass
            $this->askDocument();
        } else {
            $this->ask($message, function (Answer $answer) {
                $this->student->email = $answer->getText();
                $this->student->save();

                // pass
                $this->askDocument();
            });
        }
    }

    public function askDocument()
    {
        $message = "Só falta uma informação! Preciso do seu CPF para que eu possa gerar a fatura.";

        if ($this->student->document) {
            $this->say("Já tenho seu CPF: {$this->student->document}.");
            // pass
            $this->askZipCode();
        } else {
            $this->ask($message, function (Answer $answer) {
                $this->student->document = $answer->getText();
                $this->student->save();

                // pass
                $this->askZipCode();
            });
        }
    }

    public function askZipCode()
    {
        $message = "Já ia me esquecendo, me fala seu CEP, pois vou precisar de um endereço.";

        if ($this->student->zip) {
            $this->say("Já tenho seu CEP: {$this->student->zip}.");
        } else {
            $this->ask($message, function (Answer $answer) {
                $this->student->zip = $answer->getText();
                $this->student->save();

            });
        }

        $this->address = dispatch_now(new SearchZip($this->student->zip));

        $this->say('Confira seus dados:');
        $this->say(implode("\n", $this->address));

        $this->say('Vou procurar sua fatura...');
        $this->bot->typesAndWaits(3);
        if ($this->student->invoice) {
            $this->say('Conferi aqui e já tenho também sua fatura, está tudo certo!');
        }
    }

    public function offerReceiptUpload($tryAgain = false)
    {
        $message = 'Você já está inscrito, porém ainda não identifiquei o comprovante! Me manda ai que anexo ela ao seu cadastro';
        $this->askForImages($tryAgain ? 'Tente novamente' : $message, function ($images) {
            // valid images

            foreach ($images as $image) {
                /** @var Image $image */
                $this->student->invoice = $image->getUrl();
                $this->student->save();
            }

            $this->say('Prontinho meu amigo!');

        }, function (Answer $answer) {
            // invalid images
            $this->say('Desculpe, mas não pude entender a imagem enviada.');

            $this->offerReceiptUpload(true);
        });

    }

    /**
     * @return mixed
     */
    public function run()
    {

        $user = $this->bot->getUser();

        $student = (new Student)->where('profile_id', $user->getId())->first();

        // start a new model instance
        $this->student = $student ?? (new Student());

        if ($this->student->id && !$this->student->invoice) {
            $this->offerReceiptUpload();
        } else {
            // redirect to welcome
            $this->welcomeStudent();
        }
    }
}