<?php

namespace App\Controllers;

use App\Core\Controller;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Dotenv\Dotenv;

class ContactController extends Controller
{
    public function index()
    {
        $this->view('contact/index-view');
    }

    public function send()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupération + sanitation
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];
        if ($name === '') {
            $errors[] = 'Le nom est requis.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L’email est invalide.';
        }
        if ($message === '') {
            $errors[] = 'Le message est requis.';
        }

        if ($errors) {
            $_SESSION['contact_errors'] = $errors;
            header('Location: /contact');
            exit;
        }

        // Charge .env et récupère de vraies strings
        (new Dotenv())->usePutenv()->load(__DIR__ . '/../../.env');

        $dsn        = getenv('MAILER_DSN');
        $from       = getenv('MAIL_FROM');
        $adminEmail = getenv('ADMIN_EMAIL');

        if (!$dsn || !$from || !$adminEmail) {
            throw new \RuntimeException('Veuillez définir MAILER_DSN, MAIL_FROM et ADMIN_EMAIL dans votre .env');
        }

        // Initialise le mailer
        $transport = Transport::fromDsn($dsn);
        $mailer    = new Mailer($transport);

        // 1) Envoi au admin
        $mailToAdmin = (new Email())
            ->from($from)
            ->to($adminEmail)
            ->subject('Nouveau message de contact')
            ->text(
                "Nom    : $name\n" .
                "Email  : $email\n\n" .
                "Message:\n$message"
            );
        $mailer->send($mailToAdmin);

        // 2) Confirmation au visiteur
        $confirmation = (new Email())
            ->from($from)
            ->to($email)
            ->subject('Confirmation de réception de votre message')
            ->text(
                "Bonjour $name,\n\n" .
                "Merci pour votre message. Nous vous répondrons au plus vite.\n\n" .
                "Cordialement,\nL’équipe Medmanager"
            );
        $mailer->send($confirmation);

        $_SESSION['success_message'] = 'Votre message a été envoyé avec succès.';
        header('Location: /contact');
        exit;
    }
}
