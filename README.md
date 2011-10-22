# ValidationAuths for CakePHP2.0 #

## MultivalidatableAuthenticate ##

MultivalidatableBehavior対応の認証処理です。
http://bakery.cakephp.org/articles/dardosordi/2008/07/29/multivalidatablebehavior-using-many-validation-rulesets-per-model

    <?php
        App::uses('Controller', 'Controller');
        class AppController extends Controller {
            public $components = array('Session', 'Auth');

            public function beforeFilter() {
                parent::beforeFilter();

                // 認証設定
                $this->Auth->authenticate = array(
                    'Multivalidatable' => array(
                        'fields' => array(
                            'username' => 'username',
                            'password' => 'password'
                        ),
                        'userModel' => 'Operator',
                        'scope' => array(
                            'User.valid_flg' => true,
                        ),
                        'realm' => '',
                        'validationRule' => 'login_auth',
                        'passwordHash' => false,
                    ),
                );

                // ログイン設定
                $this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
                $this->Auth->loginRedirect = array('controller' => 'dashboards', 'action' => 'index');

                // logout後のページ指定
                $this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'logout');

                // ログインユーザーの情報
                $this->set('login_user', $this->Auth->user());
            }


    <?php
        App::uses('AppController', 'Controller');
        class UsersController extends Controller {

        public function beforeFilter() {
            parent::beforeFilter();

            // 未認証アクセスを許可するaction
            $this->Auth->allow('login', 'logout');
        }

        public function login() {
            // ログイン処理
            if ($this->request->is('post')) {
                if ($this->Auth->login()) {
                    return $this->redirect($this->Auth->redirect());
                }
            }
        }

        public function logout() {
            // ログアウト処理
            $this->redirect($$this->Auth->logout());
        }


