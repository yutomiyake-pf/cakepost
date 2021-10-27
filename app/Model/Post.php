<?php 

App::uses('AppModel','Model');

class Post extends AppModel{

    public $useTable= "posts";

    //Memberモデルと関連付け
    public $belongsTo = [
        'Member' => [
            'className' => 'Member',
            'foreignKey' => 'member_id'
        ]
    ];

    public $validate = [
        'message' => [
            [
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'メッセージを入力してください'
            ],
            [
                'rule' => ['maxLength',100],
                'message' => '100文字を超えています'
            ],
            [
                'rule' => ['minLength',1],
                'message' => '1文字以上入力してください'
            ],   
        ],
    ];

    //投稿する
    public function postMessage($message,$userId){

        $messageData = $this->save([
            'message' => $message,
            'member_id' => $userId,
        ]);
        return $messageData;
    }

    //messageを取得
    public function getMessage($id){

        $messageData = $this->find('first',['conditions' => ['Post.id' => $id]]);

        return $messageData;
    }

    //返信する
    public function reply($message,$userId,$replyPostId){

        $replyData = $this->save([
            'message' => $message,
            'member_id' => $userId,
            'reply_post_id' => $replyPostId
        ]);
        return $replyData;
    }

    
}