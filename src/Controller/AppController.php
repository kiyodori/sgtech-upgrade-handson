<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password'],
                ],
            ],
            'loginAction' => ['controller' => 'Users', 'action' => 'login'],
            'loginRedirect' => ['controller' => 'Dashboard', 'action' => 'index'],
            'logoutRedirect' => ['controller' => 'Users', 'action' => 'login'],
            'authError' => 'ログインしてください。',
        ]);
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // ヘッダーにログイン中の社員名を出す
        $this->set('currentUser', $this->Auth->user());
    }
}
