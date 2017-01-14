<?php

namespace LTDBeget\Rush;


use Hoa\Console\Cursor;
use Hoa\Console\Input;
use Hoa\Console\Output;
use LTDBeget\Rush\UI\Window;


class Readline
{

    /**
     * @var CompleteInterface
     */
    protected $completer;

    /**
     * @var InputBuffer
     */
    protected $buffer;

    /**
     * @var Input
     */
    protected $input;

    /**
     * @var Output
     */
    protected $output;

    /**
     * @var Window
     */
    protected $window;

    /**
     * @var array
     */
    protected $keymap;


    public function __construct()
    {
        $this->buffer = new InputBuffer();
        $this->input = new Input();
        $this->output = new Output();
        $this->window = new Window($this->output, 4);

        $this->initBinds();
    }

    public function read(string $prompt): string
    {
        $this->resetWindow();

        $this->buffer->setPrompt($prompt);

        while (true) {

            $this->printBuffer();
            $this->window->show();
            $char = $this->input->read(3);
            $this->window->hide();

//            echo "CHar :" . $char . PHP_EOL;
//            echo "CHar codde:" . ord($char) . PHP_EOL;
//            continue;
//
            //service chars processing
            if (isset($this->keymap[$char])) {
                call_user_func($this->keymap[$char], $this);
                continue;
            }

            $code = ord($char);

            //service non printable processing
            if (isset($this->keymap[$code])) {
                call_user_func($this->keymap[$code], $this);
                continue;
            }

            //char processing
            $this->buffer->insert($char);
            $this->resetWindow();
        }

        return $this->buffer->getCurrent();
    }

    public function setCompleter(CompleteInterface $completer)
    {
        $this->completer = $completer;
    }

    /**
     * @param int|string $value ASCII code| String value
     * @param string $handler Function name
     */
    public function bindKey($value, string $handler)
    {
        $this->keymap[$value] = [$this, $handler];
    }

    protected function initBinds()
    {
        /** @uses \LTDBeget\Rush\Readline::bindBackspace() */
        $this->bindKey(127, 'bindBackspace');

        /** @uses \LTDBeget\Rush\Readline::bindLF() */
        $this->bindKey(10, 'bindLF');

        /** @uses \LTDBeget\Rush\Readline::bindArrowUp() */
        $this->bindKey("\033[A", 'bindArrowUp');

        /** @uses \LTDBeget\Rush\Readline::bindArrowRight() */
        $this->bindKey("\033[C", 'bindArrowRight');

        /** @uses \LTDBeget\Rush\Readline::bindArrowDown() */
        $this->bindKey("\033[B", 'bindArrowDown');

        /** @uses \LTDBeget\Rush\Readline::bindArrowLeft() */
        $this->bindKey("\033[D", 'bindArrowLeft');

    }

    protected function bindBackspace(Readline $self)
    {
        $this->buffer->removeChar();

        $this->resetWindow();
    }

    protected function bindLF(Readline $self)
    {
        if ($self->window->isActive()) {
            $value = $self->window->getValue();
            $current = $this->buffer->getCurrent();

            $info = new InputInfo($current);
            //TODO Рассмотреть что дополняем и по ситуации ставить в конце пробел и смещать курсор за скобки,
            // если это например значение опции
            //TODO всего три кейса: аргумент, опция, значение опции
            // пока только аргумент
            // в буффер должна быть вставка с учетом внутренней позиции
            $current = $info->getCurrent();
            $offset = ($current !== InputBuffer::EMPTY) ? strlen($current) : 0;
            $complition = substr($value, $offset);
            $this->buffer->insert($complition);

            $this->resetWindow();
        } else {
            // код запуска команды

            $self->output->writeString(PHP_EOL);
            $self->output->writeString("Command executed");
            $self->output->writeString(PHP_EOL);

            $self->buffer->reset();
        }
    }

    protected function bindArrowUp(Readline $self)
    {
        $self->window->scrollUp();
    }

    protected function bindArrowRight(Readline $self)
    {
        $this->buffer->next();
    }

    //TODO для скрола добавить чеки что окно что то покащывпет
    protected function bindArrowDown(Readline $self)
    {
        $self->window->scrollDown();
    }

    protected function bindArrowLeft(Readline $self)
    {
        $this->buffer->prev();
    }

    protected function resetWindow()
    {
        $this->window->loadContent($this->getComplete());
    }

    protected function printBuffer()
    {
        Cursor::clear('line');
        $this->output->writeString($this->buffer->getValue());
        Cursor::move("LEFT");
        Cursor::move('right', $this->buffer->getAbsolutePos());
    }

    /**
     * @return array
     */
    protected function getComplete(): array
    {
        return $this->completer->complete(new InputInfo($this->buffer->getCurrent()));
    }

}