<?php

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        require __DIR__ . '/../views/layouts/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }
}
