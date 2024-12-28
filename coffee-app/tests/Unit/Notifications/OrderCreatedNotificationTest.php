<?php

namespace Tests\Unit\Notifications;

use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderCreatedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderCreatedNotificationTest extends TestCase
{
    public function testShouldSendOrderCreatedNotification()
    {
        Notification::fake();

        $order = Order::factory()->create([
            'full_name' => 'John Doe',
            'code' => 'ORDER123',
            'email' => 'john@example.com',
        ]);

        $user = User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $notification = new OrderCreatedNotification($order);

        Notification::send($user, $notification);

        Notification::assertSentTo(
            $user,
            OrderCreatedNotification::class,
            function (OrderCreatedNotification $notification, array $channels, $notifiable) use ($order) {
                $mailData = $notification->toMail($notifiable)->toArray();
                return $mailData['greeting'] === 'Dear John Doe,' &&
                    $mailData['subject'] === 'New Order Received | Code ORDER123' &&
                    $mailData['introLines'][0] === 'We are getting started on your order right away.' &&
                    $mailData['introLines'][1] === 'Your Order Code is ORDER123' &&
                    $mailData['actionText'] === 'See Order Confirmation' &&
                    $mailData['actionUrl'] === $order->getCheckoutConfirmtionPath() &&
                    $mailData['outroLines'][0] === 'Thank you for using our application!';
            }
        );
    }
}