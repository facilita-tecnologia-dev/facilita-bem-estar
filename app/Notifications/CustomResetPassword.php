<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    public function __construct(protected string $token, protected string $guard = 'user') {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $route = $this->guard == 'company' ? 'company.password.reset' : 'user.password.reset';
        
        $url = url(route($route, [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Recupere sua senha')
            // ->greeting('Olá!')
            // ->line('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.')
            // ->action('Redefinir Senha', $url)
            // ->line('Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.')
            // ->salutation('Atenciosamente, Facilita Saúde Mental');
            ->view('emails.forgot-password', [
                'url' => $url,
                'user' => $notifiable,
            ]);
    }
}
