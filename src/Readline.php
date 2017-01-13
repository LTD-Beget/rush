<?php

namespace LTDBeget\Rush;


use Hoa\Console\Cursor;
use Hoa\Console\Input;
use Hoa\Console\Output;
use LTDBeget\Rush\UI\Window;
use LTDBeget\Rush\Utils\Console;
use SebastianBergmann\CodeCoverage\Report\PHP;

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
    protected $input;
    protected $x;
    protected $window;

    protected $keymap;
    protected $isNewInput;

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
        $this->isNewInput = true;
        $this->buffer->clear();

        $this->resetWindow();

        while (true) {

            $this->resolvePrompt($prompt);

            $this->window->show($this->x + $this->buffer->getPos());
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
            $this->output->writeString($char);
            $this->resetWindow();
        }

        return $this->buffer->getValue();
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
        /** @uses Readline::bindBackspace() */
        $this->bindKey(127, 'bindBackspace');

        /** @uses Readline::bindLF() */
        $this->bindKey(10, 'bindLF');

        /** @uses Readline::bindArrowUp() */
        $this->bindKey("\033[A", 'bindArrowUp');

        /** @uses Readline::bindArrowRight() */
        $this->bindKey("\033[C", 'bindArrowRight');

        /** @uses Readline::bindArrowDown() */
        $this->bindKey("\033[B", 'bindArrowDown');

        /** @uses Readline::bindArrowLeft() */
        $this->bindKey("\033[D", 'bindArrowLeft');

    }

    protected function bindBackspace(Readline $self)
    {
        if ($this->buffer->removeChar()) {
            Cursor::move("left");
            Cursor::clear("right");
        }

        $this->resetWindow();
    }

    protected function bindLF(Readline $self)
    {
        if ($self->window->isActive()) {
            $value = $self->window->getValue();
            $current = $this->buffer->getValue();

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
            $self->output->writeString($complition);
            $this->resetWindow();
        } else {
            $self->isNewInput = true;
            $self->output->writeString(PHP_EOL);
            $self->buffer->clear();
        }
    }

    protected function bindArrowUp(Readline $self)
    {
        $self->window->scrollUp();
    }

    protected function bindArrowRight(Readline $self)
    {
        Cursor::move("right");
        $this->buffer->next();
    }

    //TODO для скрола добавить чеки что окно что то покащывпет
    protected function bindArrowDown(Readline $self)
    {
        $self->window->scrollDown();
    }

    protected function bindArrowLeft(Readline $self)
    {
        Cursor::move("left");
        $this->buffer->prev();
    }

    protected function resolvePrompt(string $prompt)
    {
        if ($this->isNewInput) {
            $this->output->writeAll($prompt);
            $this->isNewInput = false;
            $this->x = $this->getPosCursorX() - 1;
        }
    }

    protected function resetWindow()
    {
        $this->window->resetScrolling();
        $this->window->loadContent($this->getComplete());
    }

    /**
     * @return array
     */
    protected function getComplete(): array
    {
        return $this->completer->complete(new InputInfo($this->buffer->getValue()));
    }

    protected function getPosCursorX()
    {
        return Cursor::getPosition()['x'];
    }

}