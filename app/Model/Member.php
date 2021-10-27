<?php 

App::uses('AppModel','Model');

class Member extends AppModel {

    public $useTable= "members";

    public $validate = array(
        'username' => array(
            array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'ニックネームを入力してください'
            ),
            array(
                'rule' => array('minLength', 2),
                'message' => '2文字以上で入力してください'
            ),
            array(
                'rule' => array('maxLength', 20),
                'message' => '20文字以内で入力してください'
            ),
        ),

        'email' => array(
            array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'メールアドレスを入力してください'
            ),
            array(
                'rule' => 'email',
                'message' => '正しいメールアドレスを入力してください'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'このメールアドレスは使われています'
            ),
            array(
                'rule' => array('minLength', 4),
                'message' => '4文字以上で入力してください'
            ),
            array(
                'rule' => array('maxLength', 50),
                'message' => '50文字以内で入力してください'
            ),

        ),

        'password' => array(
            array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'パスワードを入力してください'
            ),
            array(
                'rule' => array('minLength', 4),
                'message' => '4文字以上で入力してください'
            ),
            array(
                'rule' => array('maxLength', 50),
                'message' => '50文字以内で入力してください'
            ),
            array(
                'rule' => '/^[a-z0-9]{3,}$/i',
                'message' => '半角英数字しか使えません'
            ),
        ),

        'picture' => array(
            array(
                'rule' => array(
                                'extension',array(
                                                'gif','jpg','jpeg'
                                            ),
                                'required' => true,
                            ),
                'message' => '画像は拡張子がgifかjpgかjpegのものにしてください'
            ),
            array(
                'rule' => array(
                                'mimeType',array(
                                            'image/gif','image/jpg','image/jpeg'
                                            ),
                            ),
                'message' => '画像タイプはgifかjpgかjpegのものを使ってください',
            ),

        ),
    );


    //パスワードとメールが一致するものがあるか(ログイン)
    public function login($email,$password){
        //退会フラグが立っているユーザーは取得しない
        $userInfo = $this->find('first',['conditions' => ['email' => $email, 'password' => $password, 'deleteflg' => 0]]);
        
        return $userInfo;
    }

        //パスワードハッシュ化
    public function beforeSave($options = array()){
        if(!empty($this->data[$this->alias]['password'])){
            $passwordHasher = new SimplePasswordHasher(array('hashType' => 'sha256'));
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
    }

}