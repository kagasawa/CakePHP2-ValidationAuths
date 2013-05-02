<?php
/* SVN FILE: $Id$ */
/**
 * MultivalidatableAuthenticate
 * マルチバリデーション対応認証処理
 *
 * PHP 5
 *
 * @copyright COPYRIGHTS (C) 2011 Web-Promotions Limited. All Rights Reserved.
 * @link http://www.web-prom.net/
 * @package cake
 * @version $Revision$
 * @modifiedby $LastChangedBy$
 * @lastmodified $Date$
 * @license
 *
 * @author Hideyuki Kagasawa. (kagasawa@web-prom.net)
 */
App::uses('FormAuthenticate', 'Controller/Component/Auth');

class MultivalidatableAuthenticate extends FormAuthenticate {

	public $settings = array(
		'fields' => array(
			'username' => 'username',
			'password' => 'password'
		),
		'userModel' => 'User',
		'scope' => array(),
		'validationRule' => '',
		'passwordHash' => true,
                'contain' => null,
	);

	public function authenticate(CakeRequest $request, CakeResponse $response) {

        $userModel = $this->settings['userModel'];
        list($plugin, $model) = pluginSplit($userModel);

        $fields = $this->settings['fields'];
        if (empty($request->data[$model])) {
                return false;
        }

        $ModelClass = ClassRegistry::init($userModel);
        $ModelClass->set($request->data);

        if ( !empty($this->settings['validationRule']) ) {
            $ModelClass->setValidation($this->settings['validationRule']);
        }
        if ( $ModelClass->validates() ) {
            return $this->_findUser(
                $request->data[$model][$fields['username']],
                $request->data[$model][$fields['password']]
            );
        }
        return false;
	}

	protected function _password($password) {
        if ( $this->settings['passwordHash'] ) {
    		return Security::hash($password, null, true);
        } else {
            return $password;
        }
	}

}
