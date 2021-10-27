<?php 
App::uses('AppController', 'Controller');

class PostsController extends AppController{
    
    public function index(){

        $this->layout = 'post';

        // sessionコンポーネントで名前とIdをとってくる
        $userName = $this->Auth->user('username');
        $userId = $this->Auth->user('id');

        //ページネーション
        $this->paginate = [
            'limit' => 5,
            'order' => 'Post.created desc'
        ];

        //投稿を取得
        $posts = $this->paginate('Post');
        
        $this->set(compact('userName', 'userId', 'posts'));

        if ($this->request->is('post')){

            $this->Post->set($this->request->data);
            //バリでージョンに引っかからなかったら保存
            if($this->Post->validates()){

                $message = $this->request->data('Post.message');
                $userId = $this->Auth->user('id');

                //PostモデルのpostMessageメソッドで保存する
                try{
                    if(!$this->Post->postMessage($message,$userId)){
                        throw new InternalErrorException('投稿が正常に行われませんでした');
                    }

                }catch (Exception $e){
                    
                    $this->Session->setFlash('投稿に失敗しました');
                    return false;
                }
                    
                //保存したらリダイレクトする
                return $this->redirect([
                    'controller' => 'Posts',
                    'action' => 'index'
                ]);
 
            }else{

                //バリでージョンに通らなかったら
                $error = $this->Post->validationErrors;
                $this->Session->setFlash($error['message'][0]);

                return false;
            }
    
        }

    }

    //自分の投稿かどうかと投稿があるかのcheck
    private function checkUser($id){

        //idが空の場合
        if(!$id){

            throw new NotFoundException('投稿の取得に失敗しました');
        }

        $this->Post->id = $id;

        $memberId = $this->Post->read('member_id');//削除したい投稿内容のmember_idを取得
        
        $userId = $this->Auth->user('id');//ログインしているユーザーのid

        //投稿があるか
        if(!$memberId){

            throw new NotFoundException('投稿が見つかりません');
        }

        //自分の投稿か判定
        if($memberId['Post']['member_id'] !== $userId){

            throw new BadRequestException('権限がありません');

        }
    }

    //$idがあるかのチェック
    private function checkId($id){

        //idがない場合
        if(!$id){

            throw new NotFoundException('投稿が見つかりません');
        }
    }


    //削除機能
    public function delete($id){
        
        //post通信以外の場合
        if(!$this->request->is('post')){
            throw new BadRequestException('不正な通信です');

        }

        $this->checkId($id);//$idがあるかのチェック
        
        $this->checkUser($id);//自分の投稿かと投稿があるかのチェック
        
        //メッセージ削除処理
        try{
            if(!$this->Post->delete($id)){
            
                throw new InternalErrorException('削除が正常に行われませんでした');
            }
        }catch (Exception $e){
            $this->Session->setFlash('削除に失敗しました');
            return $this->redirect(['action' => 'index']);
        }

        //削除ができたら投稿画面へ
        return $this->redirect(['action' => 'index']);
    }


    //編集機能
    public function edit($id){

        $this->layout = 'post';
        
        $this->checkId($id);//$idがあるかのチェック

        $this->checkUser($id);//自分の投稿かと投稿があるかのチェック

        $userName = $this->Auth->user('username');//sessionからユーザー名を取得

        $memberMessage = $this->Post->read('message');//編集したいメッセージを取得

        $this->set(compact('userName', 'memberMessage'));//textareaに編集したいmassageをセット

        //post通信の場合
        if($this->request->is('post')){

            $this->Post->set($this->request->data);

            //バリデーションに通ったら
            if($this->Post->validates()){

                //message編集処理
                try{
                    if(!$this->Post->save($this->request->data)){

                        throw new InternalErrorException('編集が正常に行われませんでした');
                    }

                }catch (Exception $e){

                    //編集に失敗した場合はメッセージをつけて投稿一覧に戻す
                    $this->Session->setFlash('編集に失敗しました');
                    return $this->redirect(['action' => 'index']);
                }

                // 成功したら
                $this->Session->setFlash('編集が完了しました');
                $this->redirect(['action' => 'index']);

            }else{

                //バリでージョンに通らなかったら
                $error = $this->Post->validationErrors;
                $this->Session->setFlash($error['message'][0]);

                return false;
                
            }
        }
    }


    //返信機能
    public function res($id){

        $this->layout = 'post';

        $this->checkId($id);//idがあるかのチェック

        //返信したいメッセージの情報を取得
        $messageData = $this->Post->getMessage($id);

        //メッセージがない場合
        if(!$messageData){

            throw new NotFoundException('投稿が見つかりません');
        }

        $userName = $this->Auth->user('username');//sessionからユーザー名を取得

        $memberMessage = $messageData['Post']['message'];//返信したいメッセージを取得

        $memberName = $messageData['Member']['username'];//返信したいメッセージの投稿者

        $message = '@ ( ' . $memberName .' )  ' . $memberMessage;//setするmessage

        $this->set(compact('userName', 'message'));//textareaに返信したいmassageとidをセット
        
        
        //post通信の場合
        if($this->request->is('post')){

            $userId = $this->Auth->user('id'); //返信者

            $message = $this->request->data('Post.message');//返信内容

            $replyPostId = $messageData['Post']['id']; //返信したいメッセージのid

            $this->Post->set($this->request->data);

            //バリデーションに通ったら
            if($this->Post->validates()){

                //返信処理
                try{

                    if(!$this->Post->reply($message,$userId,$replyPostId)){

                        throw new InternalErrorException('返信が正常に行われませんでした');
                    }
                }catch (Exception $e){

                    $this->Session->setFlash('返信に失敗しました');
                    return $this->redirect(['action' => 'index']);
                }
                

                // 成功したら
                $this->Session->setFlash('返信が完了しました');
                $this->redirect(['action' => 'index']);

            }else{

                //バリでージョンに通らなかったら
                $error = $this->Post->validationErrors;
                $this->Session->setFlash($error['message'][0]);

                return false;
                
            }
        }

    }


    //返信元のメッセージ
    public function view($id){

        $this->layout = 'post';

        $this->checkId($id);//idがあるかのチェック

        try{

            if(!$this->Post->getMessage($id)){

                throw new InternalErrorException('この投稿は削除されています');
            }
        }catch (Exception $e){

            $this->Session->setFlash('投稿がありません');
            return $this->redirect(['action' => 'index']);
        }

        //get通信の場合
        if($this->request->is('get')){

            $messageData = $this->Post->getMessage($id);

            $memberMessage = $messageData['Post']['message'];//返信元のメッセージを取得

            $memberName = $messageData['Member']['username'];//返信元メッセージの投稿者

            $messageDate = $messageData['Post']['created'];//返信元のメッセージ投稿日

            $this->set(compact('memberMessage','memberName','messageDate'));

        }else{

            throw new BadRequestException('不正な通信です');
        }
    }
}