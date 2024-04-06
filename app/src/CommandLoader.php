<?php

namespace App;

use Symfony\Component\Console\Attribute\AsCommand;

class CommandLoader
{
    public static function load(): array
    {
        $patternPath = SRC_PATH . 'UserInterface' . '/' . 'Console' . '/' . '*Command.php';
        $self = new self();
        $namespaces = $self->createNamespace($patternPath);
        return $self->createCommandMap($namespaces);
    }

    /**
     * @return string[]
     */
    private function createNamespace(string $patternPath): array
    {
        $result = [];

        $files = glob($patternPath);
        foreach ($files as $file) {
            include $file;
            $relativePath = substr($files[0], strlen(SRC_PATH));
            $namespace = str_replace(['/', '.php'], ['\\', ''], $relativePath);
            $namespace = 'App\\' . $namespace;
            $result[] = $namespace;
        }

        return $result;
    }

    private function createCommandMap(array $namespaces): array
    {
        $result = [];

        foreach ($namespaces as $namespace) {
            if ($attribute = (new \ReflectionClass($namespace))->getAttributes(AsCommand::class)) {
                $result[$attribute[0]->newInstance()->name] = $namespace;
            }
        }

        return $result;
    }
}
