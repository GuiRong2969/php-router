<?php

namespace Guirong\Route;

/**
 * 容器类，使用该类来实现自动依赖注入
 * @auth:GuiRong
 * @date:2022/08/27
 */

class Ioc
{

    /**
     * 作用域
     * @var string
     */
    private static $scope = 'route';

    public static function setScope($value)
    {
        self::$scope = $value;
    }

    /**
     * 注册服务集合
     * @var array
     */
    protected static $services = [];

    /**
     * 注册一个实例
     * @param $alias
     * @param $generator
     */
    public static function register($alias, $generator, $constructParams = [])
    {
        if ($generator instanceof \Closure) {
            self::$services[self::$scope][$alias] = $generator;
        } else {
            self::$services[self::$scope][$alias] = self::build($generator, $constructParams);
        }
        return self::$services[self::$scope][$alias];
    }

    /**
     * 销毁一个实例
     * @param string $class
     * @return boolean
     */
    public static function destory($class)
    {
        if (isset(self::$services[self::$scope][$class])) {
            unset(self::$services[self::$scope][$class]);
        }
        return true;
    }

    /**
     * 通过反射构建服务实现
     * @param string $className
     * @param array $constructParams
     * @return object|null
     */
    protected static function build($className, $constructParams = [])
    {
        $methodParams = $constructParams + self::getMethodParams($className);
        return (new \ReflectionClass($className))->newInstanceArgs($methodParams);
    }

    /**
     * 获得类的对象实例
     * @param string $className
     * @param array $constructParams
     * @return object|null
     */
    public static function make($className, $constructParams = [])
    {
        if (isset(self::$services[self::$scope][$className])) {
            $instance = self::$services[self::$scope][$className];
        } else {
            $instance = self::register($className, $className, $constructParams);
        }
        return $instance;
    }

    /**
     * 执行类的方法
     * @param string $class [类名/注册的别名]
     * @param string $methodName    [方法名称]
     * @param array $params   [额外的参数]
     * @param array $constructParams    [构造器的参数]
     */
    public static function call($class, $methodName, $params = [], $constructParams = [])
    {
        // 获取类的实例
        $instance = self::make($class, $constructParams);
        $className = get_class($instance);
        // 获取该方法所需要依赖注入的参数
        $paramsArr = self::getMethodParams($className, $methodName);
        $params = $params ?: $paramsArr;
        // 执行类的方法
        try {
            $method = new \ReflectionMethod($className, $methodName);
            if ($method->isPublic()) {
                /**
                 * 另外两种执行类的方法
                 * 1. $instance->{$methodName}(...$params);
                 * 2. return call_user_func_array([$instance, $methodName], $params);
                 */
                return $method->invokeArgs($instance, $params);
            } else {
                throw new \ReflectionException("method $className->$methodName() is not public!");
            }
        } catch (\ReflectionException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 获得类的方法参数，只获得有类型的参数
     * @param string $className  [类名]
     * @param string $methodsName [方法名称]
     * @return array
     */
    public static function getMethodParams($className, $methodsName = '__construct')
    {
        // 通过反射获得该类
        $class = new \ReflectionClass($className);
        $paramArr = []; // 记录参数，和参数类型
        // 判断该类是否有构造函数
        if ($class->hasMethod($methodsName)) {
            // 获得构造函数
            $construct = $class->getMethod($methodsName);
            // 判断构造函数是否有参数
            $params = $construct->getParameters();
            if (count($params) > 0) {
                // 判断参数类型
                foreach ($params as $key => $param) {
                    if ($paramClass = $param->getClass()) {
                        // 获得参数类型名称
                        $paramClassName = $paramClass->getName();
                        // 获得参数类型
                        $args = self::getMethodParams($paramClassName);
                        $paramArr[] = (new \ReflectionClass($paramClass->getName()))->newInstanceArgs($args);
                    } else if ($param->isDefaultValueAvailable()) {
                        $paramArr[] = $param->getDefaultValue();
                    } else {
                        $paramArr[] = null;
                    }
                }
            }
        }
        return $paramArr;
    }
}
