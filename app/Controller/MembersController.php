<?php 
App::uses('AppController', 'Controller');

class MembersController extends AppController{

  //新規登録
    public function register(){

        if($this->request->is('post')){

            $this->Member->set($this->request->data);
      
            //バリデーションを通った時の処理
            if($this->Member->validates()){
        
                //変数にセット
                $name = $this->request->data['Member']['username'];
                $email = $this->request->data['Member']['email'];
                $password = $this->request->data['Member']['password'];
                $picture = $this->request->data['Member']['picture'];

                //ファイルアップロード
                $newPictureName = date('YmdHis') . $picture['name'];
                $path = WWW_ROOT . "img/Member_image/";

                //失敗したら登録画面に飛ばす
                if(!move_uploaded_file($picture['tmp_name'], $path . $newPictureName)){
                    $this->Session->setFlash('画像アップロードに失敗しました。');
                    return true;
                }

                //データベースフィールドに格納
                $data = [
                    'username' => $name,
                    'email' => $email,
                    'password' => $password,
                    'picture' => $newPictureName
                ];
        
                //保存処理
                try{
                    if($this->Member->save($data, false) == false){
                        throw new InternalErrorException('登録が正常に行われませんでした');
                    }

                }catch (Exception $e){
                    //登録に失敗したら登録画面に飛ばす
                    $this->Session->setFlash('登録に失敗しました。');
                    return true;
                }
                    
                return $this->redirect([
                    'controller' => 'Members',
                    'action' => 'thanks'
                ]);
                
            }
        }
    }

    public function thanks(){
      
    }

    public function login(){
        //post送信された場合のみログイン
        if($this->request->is('post')){
            $password = $this->request->data('Member.password');
            $email = $this->request->data('Member.email');

            if($password && $email){
                //パスワードをハッシュ化する(登録の際、自動でハッシュ化されている)
                $passwordHasher = new SimplePasswordHasher(['hashType' => 'sha256']);
                $password = $passwordHasher->hash($password);
                //Memberモデルのloginメソッドの引数に設定
                if($userInfo = $this->Member->login($email,$password)){
                
                    //loginできたらSessionに保存
                    $this->Auth->login([
                        'id' => $userInfo['Member']['id'],
                        'username' => $userInfo['Member']['username'],
                        'email' => $userInfo['Member']['email'],
                        'picture' => $userInfo['Member']['picture']
                    ]);

                    return $this->redirect([
                        'controller' => 'Posts',
                        'action' => 'index'
                    ]);
                }else{
                    $this->Session->setFlash('パスワードとメールアドレスが不正です');
                    return false;
                }
                  
            }else{
                $this->Session->setFlash('パスワードとメールアドレスを入力してください');
                return false;
            }
        }
    }

    public function logout(){
        
        return $this->redirect($this->Auth->logout());
    }


    //退会
    public function deleteMember(){

        $userId = $this->Auth->user('id');

        $this->Member->id = $userId;

        //deleteflgの更新
        try{

            if(!$this->Member->saveField('deleteflg','1')){

                throw new InternalErrorException('退会が正常に行われませんでした');
            }
        }catch (Exception $e){

            $this->Session->setFlash('退会に失敗しました');
            return $this->redirect(['controller' => 'Posts', 'action' => 'index']);
        }

        return $this->redirect($this->Auth->logout());//退会したらログアウト

    }
}