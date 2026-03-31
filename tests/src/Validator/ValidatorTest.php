<?php

namespace tests\src\Validator;

use PHPUnit\Framework\TestCase;
use src\Validator\Validator;

/**
 * Class ValidatorTest
 * @package tests\src\Validator
 */
class ValidatorTest extends TestCase
{
    private Validator $validator;
    
    protected function setUp(): void
    {
        $this->validator = new Validator();
    }
    
    public function testRequiredValidationPasses()
    {
        $result = $this->validator->required('test', 'name');
        
        $this->assertTrue($result->isValid());
    }
    
    public function testRequiredValidationFails()
    {
        $result = $this->validator->required('', 'name');
        
        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('name', $result->getErrors());
    }
    
    public function testEmailValidationPasses()
    {
        $result = $this->validator->email('test@example.com', 'email');
        
        $this->assertTrue($result->isValid());
    }
    
    public function testEmailValidationFails()
    {
        $result = $this->validator->email('invalid-email', 'email');
        
        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('email', $result->getErrors());
    }
    
    public function testMinLengthValidationPasses()
    {
        $result = $this->validator->minLength('test', 3, 'password');
        
        $this->assertTrue($result->isValid());
    }
    
    public function testMinLengthValidationFails()
    {
        $result = $this->validator->minLength('ab', 3, 'password');
        
        $this->assertFalse($result->isValid());
    }
    
    public function testMaxLengthValidationPasses()
    {
        $result = $this->validator->maxLength('test', 10, 'username');
        
        $this->assertTrue($result->isValid());
    }
    
    public function testMaxLengthValidationFails()
    {
        $result = $this->validator->maxLength('verylongusername', 10, 'username');
        
        $this->assertFalse($result->isValid());
    }
    
    public function testPasswordStrengthValidationPasses()
    {
        $result = $this->validator->passwordStrength('SecurePass123', 'password');
        
        $this->assertTrue($result->isValid());
    }
    
    public function testPasswordStrengthValidationFails()
    {
        $result = $this->validator->passwordStrength('weak', 'password');
        
        $this->assertFalse($result->isValid());
    }
    
    public function testMultipleValidations()
    {
        $this->validator
            ->required('test', 'name')
            ->email('test@example.com', 'email')
            ->minLength('test123', 6, 'password');
        
        $this->assertTrue($this->validator->isValid());
    }
    
    public function testClearErrors()
    {
        $this->validator->required('', 'name');
        $this->assertFalse($this->validator->isValid());
        
        $this->validator->clear();
        $this->assertTrue($this->validator->isValid());
    }
}
