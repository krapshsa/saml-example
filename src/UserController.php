<?php

namespace App;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class UserController
{
    private UserBackend $userBackend;
    private Session     $session;
    private Environment $twig;

    public function __construct(Environment $twig, Session $session, UserBackend $userBackend)
    {
        $this->twig        = $twig;
        $this->session     = $session;
        $this->userBackend = $userBackend;
    }

    public function defaultPage(): Response
    {
        if (!empty($this->session->get('user'))) {
            return new redirectResponse('/dashboard');
        }

        return new Response($this->twig->render('login.html.twig', [
            'errMsg' => $this->session->get('err_msg')
        ]));
    }

    public function login(): Response
    {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $result = $this->userBackend->checkPassword($username, $password);

        if ($result) {
            $this->session->set('user', $username);
            return new redirectResponse('/dashboard');
        } else {
            $this->session->set('errmsg', 'Invalid credentials');
            return new redirectResponse('/');
        }
    }

    public function dashboard(): Response
    {
        if(empty($this->session->get('user'))) {
            return new redirectResponse('/');
        }

        return new Response($this->twig->render('dashboard.html.twig', [
            'currentUser' => $this->session->get('user')
        ]));
    }
}