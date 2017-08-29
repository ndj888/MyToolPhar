<?php
/**
 * Created by JiangJiaCai.
 * User: Administrator
 * Date: 2017/8/29 0029
 * Time: 10:43
 */

namespace com_jjcbs\lib;


use com_jjcbs\exceptions\AnnotationException;
use com_jjcbs\interfaces\AnnotationMethodInterface;

/**
 * Class AnnotationMethodAbstract
 * @package com_jjcbs\lib
 */
abstract class AnnotationMethodAbstract implements AnnotationMethodInterface
{
    const DISABLE_SWITCH_NAME = 'disable';
    protected static $argv = [];
    protected static $param = [];
    protected static $input = '';

    /**
     * @param string $input
     * @return object
     */
    public static function setInput(string $input)
    {
        self::$input = $input;
    }



    public static function exec(array $argv, array $param, string $input = '')
    {
        // TODO: Implement exec() method.
        self::$argv = $argv;
        self::$param = $param;

        try{
            $funName = self::getMethodName();
            if ( self::DISABLE_SWITCH_NAME  === static::$funName(static::do()) ){
                throw new AnnotationException('the annotation not has ' . $funName . 'fun');
            }
            // return output
            return self::$input;
        }catch (AnnotationException $exception){
            self::exception($exception);
        }
    }

    /**
     * parsed method name
     * @return string
     */
    protected static function getMethodName(){
        return isset(self::$argv['methodName']) ? 'parsedMethod' : (self::$argv['varName'] ? 'parsedVar' : 'parsedClass');
    }

    /**
     * parsing method replace static $input
     * @param $data
     * @return mixed
     */
    abstract static protected function parsedMethod($data = null);
    abstract static protected function parsedClass($data = null);
    abstract static protected function parsedVar($data = null);

    /**
     * the annotation must do things
     * you must implement the method
     * @return mixed
     */
    abstract static protected function do();

    /**
     * if exception
     * @param AnnotationException $exception
     * @return mixed
     */
    abstract static protected function exception(AnnotationException $exception);

}