<?php
global $link;
global $img;
$img = '00001';
$imageNumber = 1;
if( $_GET["next"] || $_GET["prev"] || $_GET["imgNum"]) {
    $i = $_GET['imgNum'];
    mysqli_select_db($link, "imageClassificationManual") or die ("no database");
    $imageNumber = $i;
    if($_GET["x"] != -1){
        $qUpd = 'INSERT INTO bbox (imgName, boxX, boxY, boxW, boxH) VALUES('.$_GET['imgNum'].','.$_GET['x'].','.$_GET['y'].','.$_GET['w'].','.$_GET['h'].')';
        //$qUpd = 'UPDATE images SET imageX='.$_GET['x'].' WHERE imageNumber='.$_GET['imgNum'];
        $retval = mysqli_query($link, $qUpd) or die(mysqli_error());
        if(! $retval )
        {
            die('Could not update data: ' . mysqli_error());
        }
        mysqli_close($link);
    }
    if($_GET["next"]){
        $imageNumber = $i + 1;
        $img = substr((string)($imageNumber + 100000), 1);
    }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>PHP Output</title>
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

<body>
<h2>Car</h2>
<canvas id="myCanvas" width="800" height="500" style="border:1px solid #d3d3d3;">
    Your browser does not support the HTML5 canvas tag.
</canvas>

<script>
    var x = -1;
    var y = -1;
    var w = -1;
    var h = -1;
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
    function mouseUp() {
        var cls1 = prompt("Please enter class for selected box", "car");
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
    var cls;
    function draw() {
        //ctx.fillRect(rect.startX, rect.startY, rect.w, rect.h);
        //ctx.rect(rect.startX, rect.startY, rect.w, rect.h);
        ctx.strokeRect(rect.startX, rect.startY, rect.w, rect.h);
        x = rect.startX;
        y = rect.startY;
        w = rect.w;
        h = rect.h;

    }
    init();
    function next(){
        document.getElementById('cnxt').href="./index.php?imgNum=<?php echo $imageNumber ?>&next=1&x=" + x.toString() + "&y=" + y.toString() + "&w=" + w.toString() + "&h=" + h.toString();
    }
    function prev(){
        document.getElementById('cprev').href="./index.php?imgNum=<?php echo $imageNumber ?>&prev=1&x=" + x.toString() + "&y=" + y.toString() + "&w=" + w.toString() + "&h=" + h.toString();
    }
</script>

<a href = "" id="cprev" onclick=prev()>Previous</a>
<a href = "" id="cnxt" onclick=next()>Next</a>

</body>
</html>