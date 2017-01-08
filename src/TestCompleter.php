<?php

namespace LTDBeget\Rush;


class TestCompleter implements CompleteInterface
{

    /**
     * @param InputInfoInterface $info
     * @return array
     */
    public function complete(InputInfoInterface $info): array
    {
        $pos = $info->getPos();
        $args = $info->getArgs();
        $options = $info->getOptions();

        if($pos === 0) {
            if(isset($args[0])) {
                return $this->filter($args[0], $this->getCommands());
            }

            return $this->getCommands();
        }

        if($pos === 1) {
            if(isset($args[1])) {
                return $this->filter($args[1], $this->getArguments());
            }

            return $this->getArguments();
        }

    }

    public function getCommands()
    {
        return [
            'asset/compress',
            'asset/template',
            'cache/flush',
            'cache/flush-all',
            'cache/flush-schema',
            'cache/index',
            'fixture/load',
            'fixture/unload',
            'help/index',
            'message/config',
            'message/config-template',
            'message/extract',
            'migrate/create',
            'migrate/down',
            'migrate/history',
            'migrate/mark',
            'migrate/new',
            'migrate/redo',
            'migrate/to',
            'migrate/up',
            'serve/index'
        ];
    }

    protected function getArguments()
    {
        return [
            'name',
            'email',
            'age',
            'city'
        ];
    }

    protected function filter(string $search, array $handle): array
    {
        return array_filter($handle, function($item) use ($search) {
            return strpos($item, $search) === 0;
        });
    }

}