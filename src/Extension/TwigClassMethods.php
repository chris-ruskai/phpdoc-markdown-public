<?php

namespace Cvuorinen\PhpdocMarkdownPublic\Extension;

use phpDocumentor\Descriptor\ClassDescriptor;
use phpDocumentor\Descriptor\MethodDescriptor;
use Twig_Extension;
use Twig_SimpleFunction;

/**
 * Twig extension to get only the public methods from a \phpDocumentor\Descriptor\ClassDescriptor instance.
 *
 * Adds the following function to Twig:
 *
 *  publicMethods(ClassDescriptor class): MethodDescriptor[]
 */
class TwigClassMethods extends Twig_Extension
{
    const NAME = 'TwigClassMethods';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('publicMethods', array($this, 'getPublicMethods')),
            new Twig_SimpleFunction('protectedMethods', array($this, 'getProtectedMethods')),
            new Twig_SimpleFunction('privateMethods', array($this, 'getPrivateMethods')),
            new Twig_SimpleFunction('constants', array($this, 'getConstants')),
        );
    }

    /**
     * @param ClassDescriptor $class
     *
     * @return MethodDescriptor[]
     */
    public function getPublicMethods($class)
    {
        if (!$class instanceof ClassDescriptor) {
            return [];
        }

        $methods = $class->getMagicMethods()
            ->merge($class->getInheritedMethods())
            ->merge($class->getMethods());

        return array_filter(
            $methods->getAll(),
            function (MethodDescriptor $method) {
                return $method->getVisibility() === 'public';
            }
        );
    }

    /**
     * @param ClassDescriptor $class
     *
     * @return MethodDescriptor[]
     */
    public function getProtectedMethods($class)
    {
        if (!$class instanceof ClassDescriptor) {
            return [];
        }

        $methods = $class->getMagicMethods()
            ->merge($class->getInheritedMethods())
            ->merge($class->getMethods());

        return array_filter(
            $methods->getAll(),
            function (MethodDescriptor $method) {
                return $method->getVisibility() === 'protected';
            }
        );
    }


    /**
     * @param ClassDescriptor $class
     *
     * @return MethodDescriptor[]
     */
    public function getPrivateMethods($class)
    {
        if (!$class instanceof ClassDescriptor) {
            return [];
        }

        $methods = $class->getMagicMethods()
            ->merge($class->getInheritedMethods())
            ->merge($class->getMethods());

        return array_filter(
            $methods->getAll(),
            function (MethodDescriptor $method) {
                return $method->getVisibility() === 'private';
            }
        );
    }
    /**
     * @param ClassDescriptor $class
     *
     * @return MethodDescriptor[]
     */
    public function getConstants($class){
        if (!$class instanceof ClassDescriptor) {
            return [];
        }
        return $class->getConstants();
    }
}
