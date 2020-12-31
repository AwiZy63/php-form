<?php

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

require __DIR__.'/../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__.'/../templates');

$twig = new Environment($loader, [
    'debug' => true,
    'strict_variables' => true,
]);

$twig->addExtension(new DebugExtension());

$formData = [
    'email' => '',
    'subject' => '',
    'message' => '',
];

$errors = [];

if ($_POST) {
    foreach ($formData as $key => $value) {
        if (isset($_POST[$key])) {
            $formData[$key] = $_POST[$key];
        }
    }
}

    $minLength = 3;
    $maxLength = 190;
    $maxLengthText = 1000;
    if (empty($_POST['email'])) {
        $errors['email'] = 'merci d\'indiquer votre adresse email';
    } elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        $errors['email'] = 'merci de renseigner un email valide';
    } elseif (strlen($_POST['email']) > $maxLength) {
        $errors['email'] = "votre email est trop long, il doit comprendre moins de {$maxLength} caractères";
    }
    if (empty($_POST['subject'])) {
        $errors['subject'] = 'Vous devez préciser l\'objet de votre message';
    } elseif (strlen($_POST['subject']) < $minLength || strlen($_POST['subject']) > $maxLength) {
        $errors['subject'] = "merci de renseigner un objet dont la longueur est comprise entre {$minLength} et {$maxLength} inclus";
    } elseif (preg_match('/^[a-zA-Z]+$/', $_POST['subject']) === 0) {
        $errors['subject'] = 'merci de renseigner un objet composé uniquement de lettres de l\'alphabet sans accent';
    }
    
    if (empty($_Post['message'])) {
        $errors['message'] = 'Vous devez entrer un message';
    } elseif (strlen($_POST['message']) < $minLength || strlen($_POST['message']) > $maxLengthText) {
        $errors['message'] = "Votre message doit comprendre entre {$minLength} et {$maxLengthText} caractères";
    }

echo $twig->render('contact.html.twig', [
    'errors' => $errors,
    'formData' => $formData,
]);


