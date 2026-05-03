<?php
class HomeController extends Controller
{
    public function index(): void
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        $this->render('home/index', ['pageTitle' => 'StudentLife Hub — Ta vie étudiante, organisée.'], false);
    }
}
