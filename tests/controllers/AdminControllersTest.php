<?php

namespace tests\controllers;

use PHPUnit\Framework\TestCase;

/**
 * Class AdminControllersTest
 *
 * @package tests\controllers
 * @covers \app\controllers\AdminController
 */
class AdminControllersTest extends TestCase {

    /**
     * @test Index action
     * @covers \app\controllers\AdminController::indexAction
     */
    public function testIndexAction() {
        $this->assertTrue(true);
    }

    public function testLoginAction() {
        $this->assertTrue(true);
    }

    public function testLogoutAction() {
        $this->assertTrue(true);
    }

}