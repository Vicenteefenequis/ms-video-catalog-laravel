<?php

namespace Tests\Unit\Domain\Validation;

use Core\Domain\Exception\EntityValidationException;
use Core\Domain\Validation\DomainValidation;
use PHPUnit\Framework\TestCase;
use Throwable;

class DomainValidationUnitTest extends TestCase
{
    public function testNotNull()
    {
        try {

            $value = '';
            DomainValidation::notNull($value);

            $this->fail();
        }catch (Throwable $thr) {
              $this->assertInstanceOf(EntityValidationException::class,$thr);
        }
    }

    public function testNotNullCustomMessageException()
    {
        try {

            $value = '';
            DomainValidation::notNull($value,'custom message error');

            $this->fail();
        }catch (Throwable $thr) {
            $this->assertInstanceOf(EntityValidationException::class,$thr, 'custom message error');
        }
    }


    public function testStrMaxLength()
    {
        try {

            $value = 'Teste';
            DomainValidation::strMaxLength($value,5,'Custom Message');

            $this->fail();
        }catch (Throwable $thr) {
            $this->assertInstanceOf(EntityValidationException::class,$thr,'Custom Message');
        }
    }

    public function testStrMinLength()
    {
        try {

            $value = 'Test';
            DomainValidation::strMinLength($value,8,'Custom Message');

            $this->fail();
        }catch (Throwable $thr) {
            $this->assertInstanceOf(EntityValidationException::class,$thr,'Custom Message');
        }
    }
    public function testStrCanNullAndMaxLength()
    {
        try {

            $value = 'teste';
            DomainValidation::strCanNullAndMaxLength($value,3,'Custom Message');

            $this->fail();
        }catch (Throwable $thr) {
            $this->assertInstanceOf(EntityValidationException::class,$thr,'Custom Message');
        }
    }
}