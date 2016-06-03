<?php
global $link;
global $img;
$img = '00001';
$imageNumber = 1;
if($_POST["imgNum"]) {
    $i = $_POST['imgNum'];
    if($_POST['imgNum'] == null) {
        $i = 1;
    }
    mysqli_select_db($link, "imageClassificationManual") or die ("no database");
    //$imageNumber = $i;
    //$cc = sizeof($_POST['label']);
    $cc = $_POST['ccc'];
    echo $cc."<br>";
    while ($cc > 0) {
        $cc -= 1;
        echo $_POST['x'][$cc]."<br>";
        echo $_POST['y'][$cc]."<br>";
        echo $_POST['w'][$cc]."<br>";
        echo $_POST['h'][$cc]."<br>";
        echo $_POST['label'][$cc+1]."<br>";
        if(($_REQUEST['x'][$cc] != -1) && ($_REQUEST['label'][$cc+1] != "***") && ($_REQUEST['label'][$cc+1] != "")){
            $qUpd = 'INSERT INTO bbox (imgName, boxX, boxY, boxW, boxH, label) VALUES(' . $_REQUEST['imgNum'] . ',' . $_REQUEST['x'][$cc] . ',' . $_REQUEST['y'][$cc] . ',' . $_REQUEST['w'][$cc] . ',' . $_REQUEST['h'][$cc] . ',' . '\'' . $_REQUEST['label'][$cc+1] . '\')';
            $retval = mysqli_query($link, $qUpd) or die(mysqli_error());
            if (!$retval) {
                die('Could not update data: ' . mysqli_error());
            }
        }
    }
    mysqli_close($link);
    if($_POST["next"] == 1){
        $imageNumber = $i + 1;
        $img = substr((string)($imageNumber + 100000), 1);
    }
    if($_POST["prev"] == 1){
        $imageNumber = $i - 1;
        $img = substr((string)($imageNumber + 100000), 1);
    }
}
?>
<html>
<head>
    <title>Classify</title>
    <meta http-equiv="content-type"
          content="text/html; charset=utf-8"/>
</head>

<script>
    var img = new Image;
    img.src = 'http://localhost/cars_test_temp/<?php echo $img ?>.jpg';
    window.onload = function() {
        var c=document.getElementById("myCanvas");
        var ctx=c.getContext("2d");
        ctx.drawImage(img,0,0,img.width,img.height, 0, 0, canvas.width, canvas.height);
    };
</script>
<link rel="stylesheet" href="./jquery-ui.css">
<script src="./jquery-1.10.2.js"></script>
<script src="./jquery-ui.js"></script>
<body>
<h2>Car</h2>
<canvas id="myCanvas" width="800" height="500" style="border:1px solid #d3d3d3;">
    Your browser does not support the HTML5 canvas tag.
</canvas>

<script>
    var x = [-1];
    var y = [-1];
    var w = [-1];
    var h = [-1];
    var img = new Image;
    img.src = 'http://localhost/cars_test_temp/<?php echo $img ?>.jpg';
    var canvas = document.getElementById('myCanvas'),
        ctx = canvas.getContext('2d'),
        rect = {},
        drag = false;

    function init() {
        canvas.addEventListener('mousedown', mouseDown, false);
        canvas.addEventListener('mouseup', mouseUp, false);
        canvas.addEventListener('mousemove', mouseMove, false);
    }
    function mouseDown(e) {
        rect.startX = e.pageX - this.offsetLeft;
        rect.startY = e.pageY - this.offsetTop;
        drag = true;
    }
    var cls = ['***'];
    var cnt = 0;
    function mouseUp() {
        $(function() {
            x[cnt] = rect.startX;
            y[cnt] = rect.startY;
            w[cnt] = rect.w;
            h[cnt] = rect.h;
            $("#dialog").dialog({
                autoOpen: true,
                buttons: {
                    Car: function() {
                        cls[cnt] = 'car';
                        drag = false;
                        $(this).dialog("close");
                    },
                    Person: function() {
                        cls[cnt] = 'person';
                        drag = false;
                        $(this).dialog("close");
                    },
                    Motorcycle: function() {
                        cls[cnt] = 'motorcycle';
                        drag = false;
                        $(this).dialog("close");
                    }
                },
                width: "400px"
            });
            cnt += 1;
        });
        if(cls[cnt-1] == null) {
            cnt -= 1;
            cls[cnt] = ['***'];
        }
        drag = false;
    }
    function mouseMove(e) {
        if (drag) {
            rect.w = (e.pageX - this.offsetLeft) - rect.startX;
            rect.h = (e.pageY - this.offsetTop) - rect.startY ;
            ctx.clearRect(0,0,canvas.width,canvas.height);
            ctx.drawImage(img, 0, 0, img.width, img.height, 0, 0, canvas.width, canvas.height);
            draw();
        }
    }
    function draw() {
        //ctx.fillRect(rect.startX, rect.startY, rect.w, rect.h);
        //ctx.rect(rect.startX, rect.startY, rect.w, rect.h);
        ctx.strokeRect(rect.startX, rect.startY, rect.w, rect.h);
    }
    init();

    function next(){
        var parametersN = {
            'x[]': x,
            'y[]': y,
            'w[]': w,
            'h[]': h,
            imgNum: <?php echo $imageNumber ?>,
            next: 1,
            ccc: cnt,
            'label[]': cls
        };
        $.post(
            './index.php',
            parametersN,
            function(data, textStatus){
                document.write(data);
            }
        ).done(function(data, textStatus) {
            document.close();
        });
    }
    function prev(){
        var parametersP = {
            'x[]': x,
            'y[]': y,
            'w[]': w,
            'h[]': h,
            imgNum: <?php echo $imageNumber ?>,
            prev: 1,
            'label[]': cls
        };
        $.post(
            './index.php',
            parametersP,
            function(data, textStatus){
                document.write(data);
            }
        ).done(function(data, textStatus) {
            document.close();
        });
    }
</script>

<a id="cprev" onclick=prev()>Previous</a>
<a id="cnxt" onclick=next()>Next</a>
<div id="dialog" style="visibility: hidden">Select a class</div>

</body>
</html>