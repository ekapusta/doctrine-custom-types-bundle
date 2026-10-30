<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions;

use GlobIterator;
use RuntimeException;
use SplFileInfo;

class FunctionRegistry
{
    private string $knownDrivers;
    private string $functionsPath;

    /** @var list<class-string<SelfDescribedFunctionNode>>|null */
    private ?array $functions = null;

    public function __construct(array $doctrineConfigurations, ?string $functionsPath = null)
    {
        $this->knownDrivers = $this->extractKnownDrivers($doctrineConfigurations);
        $this->functionsPath = $functionsPath ?: __DIR__.'/*/*Function.php';
    }

    public function getKnownDrivers(): string
    {
        return $this->knownDrivers;
    }

    /**
     * @return array<string, class-string<SelfDescribedFunctionNode>>
     *
     * @throws RuntimeException
     */
    public function getStringFunctions(): array
    {
        return $this->getFunctionsByType(SelfDescribedFunctionNode::RETURN_TYPE_STRING);
    }

    /**
     * @return array<string, class-string<SelfDescribedFunctionNode>>
     *
     * @throws RuntimeException
     */
    public function getNumericFunctions(): array
    {
        return $this->getFunctionsByType(SelfDescribedFunctionNode::RETURN_TYPE_NUMERIC);
    }

    /**
     * @return array<string, class-string<SelfDescribedFunctionNode>>
     *
     * @throws RuntimeException
     */
    public function getDatetimeFunctions(): array
    {
        return $this->getFunctionsByType(SelfDescribedFunctionNode::RETURN_TYPE_DATETIME);
    }

    private function getFunctionsByType(string $type): array
    {
        $result = [];
        foreach ($this->loadFunctions() as $className) {
            if ($this->knownDrivers && !$className::isCompatibleWith($this->knownDrivers)) {
                continue;
            }
            if ($className::getReturnType() !== $type) {
                continue;
            }
            $result[$className::getName()] = $className;
        }

        return $result;
    }

    private function extractKnownDrivers(array $doctrineConfigurations): string
    {
        $drivers = [];
        foreach ($doctrineConfigurations as $config) {
            if (isset($config['dbal']['driver'])) {
                $drivers[] = $config['dbal']['driver'];
            }
            foreach ($config['dbal']['connections'] ?? [] as $connection) {
                if (isset($connection['driver'])) {
                    $drivers[] = $connection['driver'];
                }
            }
        }

        return implode(' ', array_unique($drivers));
    }

    /**
     * @throws RuntimeException
     */
    private function loadFunctions(): array
    {
        if (is_array($this->functions)) {
            return $this->functions;
        }

        /** @var SplFileInfo $fileInfo */
        foreach (new GlobIterator($this->functionsPath) as $fileInfo) {
            $className = $this->getClassNameFromFile($fileInfo->getRealPath());
            if (!class_exists($className)) {
                throw new RuntimeException("Classname $className not exists under $this->functionsPath. Please setup your autoload carefully.");
            }
            $this->functions[] = $className;
        }

        return $this->functions ?? [];
    }

    private function getClassNameFromFile(string $path): string
    {
        $className = '';
        $content = file_get_contents($path);
        $matches = [];
        if (preg_match('/namespace\s+([^;]+)/', $content, $matches)) {
            $className .= $matches[1].'\\';
        }
        if (preg_match('/class\s+([^\s]+)/', $content, $matches)) {
            $className .= $matches[1];
        }

        return $className;
    }
}
