<?php

namespace LTDBeget\Rush;


use Hoa\Console\Cursor;
use Hoa\Console\Input;
use Hoa\Console\Output;
use LTDBeget\Rush\UI\Window;
use phpDocumentor\Reflection\Types\Callable_;


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
    protected $keyHandlers;

    /**
     * @var array
     */
    protected $completeCallbacks;


    public function __construct()
    {
        $this->buffer = new InputBuffer();
        $this->input = new Input();
        $this->output = new Output();
        $this->window = new Window($this->output, 4);

        $this->initKeyHandlers();
        $this->initCompleteCallbacks();
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
            if ($this->tryResolveAsServiceCommand($char)) {
                continue;
            }

            //char processing
            $this->buffer->insert($char);
            $this->resetWindow();
        }

    }

    public function setCompleter(CompleteInterface $completer)
    {
        $this->completer = $completer;
    }

    public function registerKeyHandler($value, Callable $handler)
    {
        $this->keyHandlers[$value] = $handler;
    }

    public function registerCompleteCallback(string $type, Callable $callback)
    {
        $this->completeCallbacks[$type] = $callback;
    }

    /**
     * @param int|string $value ASCII code| String value
     * @param string $handler Function name
     */
    protected function registerCoreKeyHandler($value, string $handler)
    {
        $this->registerKeyHandler($value, [$this, $handler]);
    }

    protected function initKeyHandlers()
    {
        /** @uses \LTDBeget\Rush\Readline::handlerTab() */
        $this->registerCoreKeyHandler(9, 'handlerTab');

        /** @uses \LTDBeget\Rush\Readline::handlerBackspace() */
        $this->registerCoreKeyHandler(127, 'handlerBackspace');

        /** @uses \LTDBeget\Rush\Readline::handlerLF() */
        $this->registerCoreKeyHandler(10, 'handlerLF');

        /** @uses \LTDBeget\Rush\Readline::bindArrowUp() */
        $this->registerCoreKeyHandler("\033[A", 'handlerArrowUp');

        /** @uses \LTDBeget\Rush\Readline::handlerArrowRight() */
        $this->registerCoreKeyHandler("\033[C", 'handlerArrowRight');

        /** @uses \LTDBeget\Rush\Readline::handlerArrowDown() */
        $this->registerCoreKeyHandler("\033[B", 'handlerArrowDown');

        /** @uses \LTDBeget\Rush\Readline::handlerArrowLeft() */
        $this->registerCoreKeyHandler("\033[D", 'handlerArrowLeft');

        /** @uses \LTDBeget\Rush\Readline::handlerQuotes() */
        $this->registerCoreKeyHandler("\"", "handlerQuotes");

    }

    protected function initCompleteCallbacks()
    {
        /** @uses \LTDBeget\Rush\Readline::callbackArg() */
        $this->registerCompleteCallback(CompleteCallbackInterface::TYPE_ARG, [$this, 'callbackArg']);
    }

    protected function handlerQuotes(Readline $self)
    {
        $self->buffer->insert("\"\"");
        $self->buffer->prev();
    }

    protected function handlerBackspace(Readline $self)
    {
        $self->buffer->removeChar();
        $self->resetWindow();
    }

    protected function handlerTab(Readline $self)
    {
        $info = $self->buffer->getInputInfo();
        $current = $info->getCurrent();
        $data = $this->getComplete();

        $this->buffer->insert($this->getCommonString($current, $data));
    }

    protected function handlerLF(Readline $self)
    {
        if ($self->window->isActive()) {
            $self->processComplete();

        } else {
            // код запуска команды

            $self->output->writeString(PHP_EOL);
            $self->output->writeString("Command executed");
            $self->output->writeString(PHP_EOL);

            $self->buffer->reset();
        }
    }

    protected function handlerArrowUp(Readline $self)
    {
        $self->window->scrollUp();
    }

    protected function handlerArrowRight(Readline $self)
    {
        $self->buffer->next();
    }

    //TODO для скрола добавить чеки что окно что то покащывпет
    protected function handlerArrowDown(Readline $self)
    {
        $self->window->scrollDown();
    }

    protected function handlerArrowLeft(Readline $self)
    {
        $self->buffer->prev();
    }

    protected function callbackArg(Readline $self)
    {
        if ($self->buffer->isEndOfInput()) {
            $self->buffer->insert(' ');
        } else {
            $self->buffer->next();
        }

    }

    protected function resetWindow()
    {
        $this->window->loadContent($this->getComplete());
    }

    /**
     * @param string $char
     * @return bool
     */
    protected function tryResolveAsServiceCommand(string $char): bool
    {
        //service chars processing
        if (isset($this->keyHandlers[$char])) {
            call_user_func($this->keyHandlers[$char], $this);
            return true;
        }

        $code = ord($char);

        //service non printable processing
        if (isset($this->keyHandlers[$code])) {
            call_user_func($this->keyHandlers[$code], $this);
            return true;
        }

        return false;
    }

    protected function processComplete()
    {
        $value = $this->window->getValue();

        $info = $this->buffer->getInputInfo();
        //TODO Рассмотреть что дополняем и по ситуации ставить в конце пробел и смещать курсор за скобки,
        // если это например значение опции
        //TODO всего три кейса: аргумент, опция, значение опции
        // пока только аргумент
        // в буффер должна быть вставка с учетом внутренней позиции
        $current = $info->getCurrent();
        $offset = ($current !== InputBuffer::EMPTY) ? strlen($current) : 0;
        $type = CompleteCallbackInterface::TYPE_ARG;
        $complition = substr($value, $offset);
        $this->buffer->insert($complition);

        if (isset($this->completeCallbacks[$type])) {
            call_user_func($this->completeCallbacks[$type], $this);
        }

        $this->resetWindow();
    }

    protected function printBuffer()
    {
        Cursor::clear('line');
        $this->output->writeString($this->buffer->getPrompt() . $this->buffer->getInput());
        Cursor::move("LEFT");
        Cursor::move('right', $this->buffer->getAbsolutePos());
    }

    /**
     * @return array
     */
    protected function getComplete(): array
    {
        return $this->completer->complete($this->buffer->getInputInfo());
    }


    /**
     * TODO temp decision, make prefix tree in future
     *
     * @param string $pattern
     * @param array $data
     * @return string
     */
    protected function getCommonString(string $pattern, array $data): string
    {
        $data = array_filter($data, function ($item) use ($pattern) {
            return strpos($item, $pattern) === 0;
        });

        $max = min(array_map('mb_strlen', $data));

        $word = array_pop($data);

        $result = "";

        for ($i = strlen($pattern); $i < $max; $i++) {

            $char = $word[$i];

            foreach ($data as $item) {
                if ($item[$i] !== $char) {
                    break 2;
                }
            }

            $result .= $char;
        }

        return $result;
    }

}