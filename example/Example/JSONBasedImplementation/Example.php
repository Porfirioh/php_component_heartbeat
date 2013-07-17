<?php
/**
 * @author stev leibelt <artodeto@arcor.de>
 * @since 2013-07-17 
 */

namespace JSONBasedImplementation;

$example = Example::create()->andRun();

class Example
{
    public static function create()
    {
        $example = new self();

        return $example;
    }

    public function andRun()
    {
        //add creation of heartbeats
        //add creation of monitor
        //implement forking
        echo 'Hello';
    }
}