<?php

namespace app\controllers;

use app\App;
use app\core\Controller;
use app\models\{Tasks, Users};
use app\widgets\{Alerts, Sorting};
use src\Validator\Validator;
use ErrorException;
use Exception;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

    /**
     * Index action
     * @return null
     * @throws ErrorException
     * @throws Exception
     */
    public function indexAction() {
        /** @var array $entry */
        if (!Users::isAuth()) {
            return $this->redirect('/admin/login');
        }

        // Generate CSRF token if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $get = App::$request['get'];
        if ($post = App::$request['post']) {
            // Verify CSRF token
            if (empty($post['csrf_token']) || $post['csrf_token'] !== $_SESSION['csrf_token']) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Неверный CSRF токен');
                $pagination = Tasks::pagination([], Sorting::getSorts(Tasks::className()), $get['page']);
                return $this->render('index', [
                    'title' => 'Админка',
                    'pagination' => $pagination,
                    'post' => $post,
                ]);
            }
            
            // Revert array
            foreach ($post as $column => $rows) {
                foreach ($rows as $id => $value) {
                    $entry[$id][$column] = $value;
                }
            }

            foreach ($entry as $id => $value) {
                $errors = false;
                
                // Use Validator for validation
                $validator = new Validator();
                $validator->required($value['name'], 'name', '(' . $id . ') Не введено имя');
                $validator->required($value['email'], 'email', '(' . $id . ') Не введен e-mail');
                $validator->email($value['email'], 'email', '(' . $id . ') Введен не верный e-mail');
                $validator->required($value['text'], 'text', '(' . $id . ') Не введен текст');
                
                if (!$validator->isValid()) {
                    foreach ($validator->getErrors() as $error) {
                        Alerts::addFlash(Alerts::TYPE_WARNING, $error);
                    }
                    $errors = true;
                }
                
                if (!$errors) {
                    $task = Tasks::findOne($id);
                    $task->setName(htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'));
                    $task->setEmail(htmlspecialchars($value['email'], ENT_QUOTES, 'UTF-8'));
                    $task->setText(htmlspecialchars($value['text'], ENT_QUOTES, 'UTF-8'));
                    $task->setStatus((int)$value['status']);
                    $task->save();
                }
            }
            Alerts::addFlash(Alerts::TYPE_SUCCESS, 'Изменения сохранены!');
        }

        $pagination = Tasks::pagination([], Sorting::getSorts(Tasks::className()), $get['page']);

        return $this->render('index', [
            'title' => 'Админка',
            'pagination' => $pagination,
            'post' => $post,
        ]);
    }

    /**
     * Login action
     * @return null
     * @throws ErrorException
     */
    public function loginAction() {
        // Generate CSRF token if not exists
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        if (Users::isAuth()) {
            return $this->redirect('/admin');
        }

        if ($post = App::$request['post']) {
            // Verify CSRF token
            if (empty($post['csrf_token']) || $post['csrf_token'] !== $_SESSION['csrf_token']) {
                Alerts::addFlash(Alerts::TYPE_WARNING, 'Неверный CSRF токен');
                return $this->render('login', [
                    'title' => 'Админка (вход)',
                    'post' => $post,
                ]);
            }
            
            // Validate input
            $validator = new Validator();
            $validator->required($post['login'], 'login', 'Введите логин');
            $validator->required($post['password'], 'password', 'Введите пароль');
            
            if (!$validator->isValid()) {
                foreach ($validator->getErrors() as $error) {
                    Alerts::addFlash(Alerts::TYPE_WARNING, $error);
                }
            }
            
            if (!Alerts::isPresent()) {
                if (!Users::signIn($post['login'], $post['password'])) {
                    Alerts::addFlash(Alerts::TYPE_WARNING, 'Не верный логин или пароль');
                } else {
                    // Regenerate CSRF token after successful login
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    return $this->redirect('/admin');
                }
            }
        }

        return $this->render('login', [
            'title' => 'Админка (вход)',
            'post' => $post,
        ]);
    }


    /**
     * Logout action
     */
    public function logoutAction() {
        // Regenerate CSRF token on logout to prevent CSRF attacks
        unset($_SESSION['csrf_token']);
        Users::logOut();

        return $this->redirect('/');
    }

}
