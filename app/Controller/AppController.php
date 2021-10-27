<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller { 
    
    public $uses = [
        'Member',
        'Post'
    ];

    public $helpers = [
        'Html',
        'Form',
        'Session'
    ];

    public $components = [
        'Session',
        'Cookie',
        'Paginator',
        'Auth' => [
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Member',
                    'passwordHashar' => [
                        'className' => 'Simple',
                        'hashType' => 'sha256'
                    ]
                ]
            ],//ログインページのパス
            'loginAction' => [
                'controller' => 'Members',
                'action' => 'login'
            ],//ログイン後のページ
            'loginRedirect' => [
                'controller' => 'Posts',
                'action' => 'index'
            ],//ログアウト後のページ
            'logoutRedirect' => [
                'controller' => 'Members',
                'action' => 'login'
            ],
        ]
        
    ];

    public function beforeFilter(){

        $this->Auth->allow('login','register','thanks');
    }

}
