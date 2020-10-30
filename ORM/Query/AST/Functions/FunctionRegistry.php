<?php

namespace Ekapusta\DoctrineCustomTypesBundle\ORM\Query\AST\Functions;

class FunctionRegistry
{

    private $knownDrivers = '';
    private $functionsPath = '.';
    private $functions = null;

    public function __construct($doctrineConfigurations, $functionsPath = null)
    {
        $this->knownDrivers = $this->extractKnownDrivers($doctrineConfigurations);
        $this->functionsPath = $functionsPath ?: __DIR__ . '/*/*Function.php';
    }

    /**
     * @return string
     */
    public function getKnownDrivers()
    {
        return $this->knownDrivers;
    }

    /**
     * @throws \RuntimeException
     */
    public function getStringFunctions()
    {
        return $this->getFunctionsByType(SelfDescribedFunctionNode::RETURN_TYPE_STRING);
    }

    /**
     * @throws \RuntimeException
     */
    public function getNumericFunctions()
    {
        return $this->getFunctionsByType(SelfDescribedFunctionNode::RETURN_TYPE_NUMERIC);
    }

    /**
     * @throws \RuntimeException
     */
    public function getDatetimeFunctions()
    {
        return $this->getFunctionsByType(SelfDescribedFunctionNode::RETURN_TYPE_DATETIME);
    }

    private function getFunctionsByType($type)
    {
        $result = [];
        foreach ($this->loadFunctions() as $className) {
            if ($this->knownDrivers && !$className::isCompatibleWith($this->knownDrivers)) {
                continue;
            }
            if ($className::getReturnType() != $type) {
                continue;
            }
            $result[$className::getName()] = $className;
        }
        return $result;
    }

    private function extractKnownDrivers($doctrineConfigurations)
    {
        $drivers = [];
        foreach ($doctrineConfigurations as $config) {
            if (isset($config['dbal']['driver'])) {
                $drivers[] = $config['dbal']['driver'];
            }
            if (empty($config['dbal']['connections'])) {
                continue;
            }
            foreach ($config['dbal']['connections'] as $connection) {
                if (isset($connection['driver'])) {
                    $drivers[] = $connection['driver'];
                }
            }
        }
        $drivers = array_unique($drivers);

        return implode(' ', $drivers);
    }

    /**
     * @throws \RuntimeException
     */
    private function loadFunctions()
    {
        if (is_array($this->functions)) {
            return $this->functions;
        }

        /* @var \SplFileInfo $fileInfo */
        foreach (new \GlobIterator($this->functionsPath) as $fileInfo) {
            $className = $this->getClassNameFromFile($fileInfo->getRealPath());
            if (!class_exists($className)) {
                throw new \RuntimeException("Classname $className not exists under $this->functionsPath. Please setup your autoload carefully.");
            }
            $this->functions[] = $className;
        }

        return $this->functions;
    }

    private function getClassNameFromFile($path)
    {
        $className = '';
        $content = file_get_contents($path);
        $matches = [];
        if (preg_match('/namespace\s+([^;]+)/', $content, $matches)) {
            $className .= $matches[1] . '\\';
        }
        if (preg_match('/class\s+([^\s]+)/', $content, $matches)) {
            $className .= $matches[1];
        }
        return $className;
    }
}
