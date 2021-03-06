<?php
/**
 * Created by JiangJiaCai.
 * User: Administrator
 * Date: 2017/8/22 0022
 * Time: 14:52
 */

namespace com_jjcbs\service;


use com_jjcbs\lib\Annotation;
use com_jjcbs\lib\Service;

class AnnotationServiceImpl extends Service
{
    protected $srcClass;
    protected $annotationInstance = null;

    public function __construct()
    {
        $this->annotationInstance = new Annotation();
    }

    /**
     * @return mixed
     */
    public function getSrcClass()
    {
        return $this->srcClass;
    }

    /**
     * @param mixed $srcClass
     * @return object
     */
    public function setSrcClass($srcClass)
    {
        $this->srcClass = $srcClass;
        return $this;
    }


    public function exec()
    {
        // TODO: Implement exec() method.
        $data = [];
        $classReflection = new \ReflectionClass($this->srcClass);
        $methodList = $this->annotationInstance->parseMethodList($classReflection);
        $classInfo = $this->annotationInstance->parseClassInfo($classReflection);
        $varList = $this->annotationInstance->parseVarList($classReflection);
        $data['fileName'] = $classReflection->getFileName();
        $data['namespace'] = $classReflection->getNamespaceName();

        // will parsing to a file cache the class.
        foreach ($methodList as $k=> $m){
            $data['methodList'][$k]['annotation'] = $this->annotationInstance->parseAnnotation($m['doc']);
            $data['methodList'][$k]['methodName'] = $m['this']->getName();
            // add method ReflectionMethod
            $data['methodList'][$k]['method'] = $m['this'];
        }

        $data['classInfo']['class'] = $classInfo['this'];
        // short class name
        $data['classInfo']['className'] = trim(str_replace($data['namespace'] , '' , $classInfo['this']->getName()) , '\\');
        $data['classInfo']['annotation'] = $this->annotationInstance->parseAnnotation($classInfo['doc']);

        // varList
        foreach ($varList as $k => $v){
            $data['varList'][$k]['annotation'] = $this->annotationInstance->parseAnnotation($v['doc']);
            $data['varList'][$k]['varName'] = $v['name'];
            $data['varList'][$k]['varType'] = $v['type'];
        }

        return $data;
    }

}