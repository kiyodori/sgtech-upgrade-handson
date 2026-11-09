<?php
namespace App\Controller;

class UsersController extends AppController
{
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error('メールアドレスかパスワードが違います。');
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
