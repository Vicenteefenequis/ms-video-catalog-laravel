<?php

namespace Tests\Unit\Domain\Notification;


use Core\Domain\Notification\Notification;
use PHPUnit\Framework\TestCase;

class NotificationUnitTest extends TestCase
{

    public function test_get_errors()
    {
        $notification = new Notification();
        $errors = $notification->getErrors();

        $this->assertIsArray($errors);
    }

    public function test_add_errors()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required'
        ]);

        $errors = $notification->getErrors();

        $this->assertCount(1, $errors);
    }

}
