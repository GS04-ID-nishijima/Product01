<?php


?>


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>APIテスト</title>
    </head>
    <body>
        <header>
           <h1>
               テスト
           </h1>
        </header>


        <h2>WebAPI</h2>
        <h3>リクエスト</h3>
        mode:<input type="text" name="mode" id="mode"><br>
        withinOneWeekFlag:<input type="text" name="withinOneWeekFlag" id="withinOneWeekFlag"><br>
        startingPointLatitude:<input type="text" name="startingPointLatitude" id="startingPointLatitude"><br>
        startingPointLongitude:<input type="text" name="startingPointLongitude" id="startingPointLongitude"><br>
        endPointLatitude:<input type="text" name="endPointLatitude" id="endPointLatitude"><br>
        endPointLongitude:<input type="text" name="endPointLongitude" id="endPointLongitude"><br>
        <br><br>
        <button data-btn-type="ajax">Data get!</button>
        <h3>結果</h3>
        <div data-result="">未取得</div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="apitest.js"></script>

    </body>
</html>
