<?php

namespace App\Controller;

use App\UserBackend;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
            return new RedirectResponse('/dashboard');
        }

        return new Response($this->twig->render('login.html.twig', [
            'errMsg' => $this->session->get('err_msg')
        ]));
    }

    public function login(): Response
    {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        if ($this->session->has('errmsg')) {
            $this->session->remove('errmsg');
        }

        $result = $this->userBackend->checkPassword($username, $password);

        if ($result) {
            $this->session->set('user', $username);
            return new RedirectResponse('/dashboard');
        } else {
            $this->session->set('errmsg', 'Invalid credentials');
            return new RedirectResponse('/');
        }
    }

    public function logout(): Response
    {
        $this->session->clear();
        return new RedirectResponse('/');
    }

    public function dashboard(): Response
    {
        if(empty($this->session->get('user'))) {
            return new RedirectResponse('/');
        }

        return new Response($this->twig->render('dashboard.html.twig', [
            'session' => $this->session->all(),
        ]));
    }
}