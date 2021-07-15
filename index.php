<?php

//デッキの作成
$suits = ['♠','♥','♦','♣'];

$faces = [];

for($i = 2; $i<11; $i++){
    $faces[]=$i;
}
$faces[]='J';
$faces[]='Q';
$faces[]='K';
$faces[]='A';

$deck = [];

//強さ判定に使用するキーを付与（2,3,...,A⇒0,1,2...,12）
foreach($suits as $suit){
    foreach($faces as $key => $face){
        $deck[] = array("key"=>$key,"face"=>$face,"suit"=>$suit);
    }
}

//手札の決定
shuffle($deck);
$cardPlayer = [];
$cardOpp = [];
$ComCard = [];
for($i = 0; $i < 2; $i++) {
    $cardPlayer[] = array_shift($deck);
}

for($i = 0; $i < 2; $i++) {
    $cardOpp[] = array_shift($deck);
}

for($i = 0; $i < 5; $i++) {
    $ComCard[] = array_shift($deck);
}

include './function.php';

$cardPlayer_target = array_merge($cardPlayer,$ComCard);
$cardOpp_target = array_merge($cardOpp,$ComCard);

function determine_hand($arr){
    $combi_array = [];
    $combi_array = combination($arr,5);
    $hand_combi = [];
    for ($i=0; $i < count($combi_array); $i++) { 
        $hand_combi[] = show_hands(sort_hands($combi_array[$i]));
    }

    return max_hands($hand_combi)[0];
}

$playerHand = determine_hand($cardPlayer_target);
$oppHand = determine_hand($cardOpp_target);

$messege;
//勝敗判定
if($playerHand[1] < $oppHand[1]){
    $messege = 'あなたの勝ちです！！';
}elseif($playerHand[1] > $oppHand[1]){
    $messege = 'あなたの負けです＞＜';
}else{
    //同役判定（ホールデム仕様なので実際には不要な条件文あり）
    switch ($playerHand[0]) {
        case 'ストレートフラッシュ':
        case 'ストレート':
            if($playerHand[2][0] > $oppHand[2][0]){
                //自分がA,2,3,4,5のストレートの場合
                if(($playerHand[2][0] == 12 && $playerHand[2][1] == 3)){
                    $messege = 'あなたの負けです＞＜';
                }else{
                    $messege = 'あなたの勝ちです！！';
                }
            }elseif($playerHand[2][0] < $oppHand[2][0]){
                //相手がA,2,3,4,5のストレートの場合
                if(($oppHand[2][0] == 12 && $oppHand[2][1] == 3)){
                    $messege = 'あなたの勝ちです！！';
                }else{
                    $messege = 'あなたの負けです＞＜';
                }
            }else{
                //自分がA,2,3,4,5のストレートの場合
                if(($playerHand[2][0] == 12 && $playerHand[2][1] == 3)){
                    //相手がA,2,3,4,5のストレートの場合
                    if(($oppHand[2][0] == 12 && $oppHand[2][1] == 3)){
                        $messege = '引き分けです';
                    }else{
                        $messege = 'あなたの負けです＞＜';
                    }
                }else{
                    //相手がA,2,3,4,5のストレートの場合
                    if(($oppHand[2][0] == 12 && $oppHand[2][1] == 3)){
                        $messege = 'あなたの勝ちです！！';
                    }else{
                        $messege = '引き分けです';
                    }
                }
            }
            break;

        default:
            for ($i=0; $i < count($playerHand[2]); $i++) { 
                if($playerHand[2][$i] > $oppHand[2][$i]){
                    $messege = 'あなたの勝ちです！！';
                    break;
                }elseif($playerHand[2][$i] < $oppHand[2][$i]){
                    $messege = 'あなたの負けです＞＜';
                    break;
                }else{
                    $messege = '引き分けです';
                }
            }
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シンプルホールデム</title>
    <style>
    h1{
        text-align:center;
    }

    .main{
        width: 400px; margin: 80px auto; text-align:center;
    }
    .result{
        margin:50px 0 
    }
    </style>
</head>
<body>
    <h1>シンプルホールデム</h1>
    <div class="main">
        <div>
            <?php 
            echo <<<EOM
            場のカード：
            {$ComCard[0]['face']}<span>{$ComCard[0]['suit']}</span>
            {$ComCard[1]['face']}<span>{$ComCard[1]['suit']}</span>
            {$ComCard[2]['face']}<span>{$ComCard[2]['suit']}</span>
            {$ComCard[3]['face']}<span>{$ComCard[3]['suit']}</span>
            {$ComCard[4]['face']}<span>{$ComCard[4]['suit']}</span><br><br><br>
            相手の手札：
            {$cardOpp[0]['face']}<span>{$cardOpp[0]['suit']}</span>
            {$cardOpp[1]['face']}<span>{$cardOpp[1]['suit']}</span><br><br>
            「{$oppHand[0]}」<br><br>
            自分の手札：
            {$cardPlayer[0]['face']}<span>{$cardPlayer[0]['suit']}</span>
            {$cardPlayer[1]['face']}<span>{$cardPlayer[1]['suit']}</span><br><br>
            「{$playerHand[0]}」
            EOM;
            ?>
        </div>
        <div class="result">
           <h2><?php echo $messege; ?></h2>
        </div>

        <input type="button"  value="もう一度遊ぶ" onclick="koshin()">
    
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <script>
    function koshin(){
        location.reload();
    }

    $("span:contains('♥')").css("color","red");
    $("span:contains('♦')").css("color","red");
    </script>
</body>
</html>
