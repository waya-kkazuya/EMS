<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class DisposalScheduleNotification extends Notification
{
    use Queueable;

    private $disposal;

    /**
     * Create a new notification instance.
     */
    public function __construct($disposal)
    {
        $this->disposal = $disposal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // 画像パスの設定
        $defaultDisk = Storage::disk();

        $imagePath = $defaultDisk->url('items/' . $this->disposal->item->image1);
        if (! $this->disposal->item->image1 || ! $defaultDisk->exists('items/' . $this->disposal->item->image1)) {
            $imagePath = $defaultDisk->url('items/No_Image.jpg');
        }

        return [
            'id'             => $this->disposal->item->id,
            'management_id'  => $this->disposal->item->management_id,
            'image_path1'    => $imagePath, //絶対URL
            'item_name'      => $this->disposal->item->name,
            'scheduled_date' => $this->disposal ? $this->disposal->disposal_scheduled_date : null,
            'message'        => '廃棄予定日が近づいています',
        ];
    }
}
