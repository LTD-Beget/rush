<?php

namespace LTDBeget\Rush;


class Completer implements CompleteInterface
{

    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ReflectorInterface
     */
    protected $reflector;

    /**
     * Completer constructor.
     * @param ParserInterface $parser
     * @param Context $context
     * @param ReflectorInterface $reflector
     */
    public function __construct(ParserInterface $parser, Context $context, ReflectorInterface $reflector)
    {
        $this->parser = $parser;
        $this->context = $context;
        $this->reflector = $reflector;
    }


    /**
     * @param InputInfo|InputInfoInterface $info
     * @return array
     * @internal param string $prev
     * @internal param string $current
     */
    public function complete(InputInfoInterface $info): array
    {
        $input = $this->buildFullInput($prev);

        $args = $this->parser->parse($input);

        $n = count($args);

        if ($n === 0) {
            return $this->reflector->commands();
        }

        if($n === 1) {
            
        }

        return $this->reflector->options($args[0]);
    }

    /**
     * @param string $input
     * @return string
     */
    protected function buildFullInput(string $input) : string
    {
        if ($this->context->isEmpty()) {
            return $input;
        }

        $context = $this->context->getContext();

        $separator = $this->reflector->getSeparator();

        if($separator === ReflectorInterface::NONE_SEPARATOR) {
            return $context . ' ' . $input;
        }

        if (strpos($context, $this->reflector->getSeparator(), -1) === strlen($input)) {
            return $context . $input;
        }
    }
}