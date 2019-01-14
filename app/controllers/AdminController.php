<?php

namespace app\controllers;

use app\App;
use app\models\Users;
use app\core\Alerts;
use app\models\Tasks;

class AdminController extends \app\core\Controller {

    public function indexAction() {
        if (!Users::isAuth()) {
            return $this->redirect('/admin/login');
        }

        $get = App::$request['get'];
        if ($post = App::$request['post']) {
            // Revetr array
            foreach ($post as $column => $rows) {
                foreach ($rows as $id => $value) {
                    $entry[$id][$column] = $value;
                }
            }

            foreach ($entry as $id => $value) {
                $errors = false;
                if (empty($value['name'])) {
                    Alerts::addFlash(Alerts::TYPE_WARNING, '(' . $id . ') Не введено имя');
                    $errors = true;
                }
                if (empty($value['email'])) {
                    Alerts::addFlash(Alerts::TYPE_WARNING, '(' . $id . ') Не введен e-mail');
                    $errors = true;
                } else {
                    if (!filter_var($value['email'], FILTER_VALIDATE_EMAIL)) {
                        Alerts::addFlash(Alerts::TYPE_WARNING, '(' . $id . ') Введен не верный e-mail');
                        $errors = true;
                    }
                }
                if (empty($value['text'])) {
                    Alerts::addFlash(Alerts::TYPE_WARNING, '(' . $id . ') Не введен текст');
                    $errors = true;
                }
                if (!$errors) {
                    $task = Tasks::findOne($id);
                    $task->setName($value['name']);
                    $task->setEmail($value['email']);
                    $task->setText($value['text']);
                    $task->setStatus($value['status']);
                    $task->save();
                }
            }
            Alerts::addFlash(Alerts::TYPE_SUCCESS, 'Изменения сохранены!');
        }

        $pagination = Tasks::pagination([], Tasks::getSorts(), $get['page']);

        return $this->render('index', [
                    'title' => 'Админка',
                    'pagination' => $pagination,
                    'post' => $post,
        ]);
    }

    public function loginAction() {
        if (Users::isAuth()) {
            return $this->redirect('/admin');
        }

        if ($post = App::$request['post']) {
            if (empty($post['login'])) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Введите логин');
            }
            if (empty($post['password'])) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Введите пароль');
            }
            if (!Alerts::isPresent()) {
                if (!Users::singIn($post['login'], $post['password'])) {
                    Alerts::addFlash(Alerts::TYPE_WARNING, 'Не верный логин или пароль');
                } else {
                    return $this->redirect('/admin');
                }
            }
        }

        return $this->render('login', [
                    'title' => 'Админка (вход)',
                    'post' => $post,
        ]);
    }

    public function logoutAction() {
        Users::singOut();

        return $this->redirect('/');
    }

}
