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

    public function test_has_errors_false()
    {
        $notification = new Notification();
        $this->assertFalse($notification->hasErrors());
    }

    public function test_has_errors_true()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'video title is required'
        ]);


        $this->assertTrue($notification->hasErrors());
    }

    public function test_message()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'title is required'
        ]);
        $notification->addError([
            'context' => 'video',
            'message' => 'description is required'
        ]);


        $message = $notification->messages();
        $this->assertIsString($message);
        $this->assertEquals(
            'video: title is required,video: description is required,',
            $message
        );
    }

    public function test_message_filter_context()
    {
        $notification = new Notification();
        $notification->addError([
            'context' => 'video',
            'message' => 'title is required'
        ]);
        $notification->addError([
            'context' => 'category',
            'message' => 'name is required'
        ]);

        $this->assertCount(2, $notification->getErrors());
        $message = $notification->messages(
            context: 'video'
        );
        $this->assertIsString($message);
        $this->assertEquals(
            'video: title is required,',
            $message
        );
    }
}
