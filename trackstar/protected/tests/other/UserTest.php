<?php
/**
 * Created by JetBrains PhpStorm.
 * User: fagor
 * Date: 08.04.13
 * Time: 22:46
 * To change this template use File | Settings | File Templates.
 */

class UserTest extends PHPUnit_Framework_TestCase {

    public function testValidateBehavioursFunction()
    {
        $this->assertTrue($this->users(0)->validatePassword('demo'));
        $this->assertFalse($this->users(0)->validatePassword('wrong'));
    }
}
