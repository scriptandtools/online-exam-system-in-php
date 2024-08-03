<?php
include_once '../dbcon.php';
session_start();
if(!isset($_SESSION['radmin'])) {
    header("location:login.php");
}

//delete user
if (@$_GET['uemail']) {
    $uemail=$_GET['uemail'];
    $sql=mysqli_query($conn,"DELETE FROM user WHERE email='$uemail'");
    $sql=mysqli_query($conn,"DELETE FROM history WHERE email='$uemail'");
    $sql=mysqli_query($conn,"DELETE FROM ranks WHERE email='$uemail'");
    header("location:dashboard.php");
}

//add question
if(isset($_REQUEST['addquiz'])) {
    if($_GET['q']=='adding') {
        $eid=$_SESSION['eid'];
        $n=$_SESSION['qns'];
        for($i=1;$i<=$n;$i++) {
            $id=uniqid();
            $qns=$_REQUEST['qns'.$i];
            $sql=mysqli_query($conn,"INSERT INTO question (eid, id, qns) VALUES ('$eid', '$id', '$qns')");
            for($j=97;$j<=100;$j++) {
                $optionid=uniqid();
                $option=$_REQUEST[chr($j).$i];
                $sql=mysqli_query($conn,"INSERT INTO options (optionid, qid, option) VALUES ('$optionid', '$id', '$option')");
                $a=$_REQUEST['ans'.$i];
                switch($a) {
                    case 'a':
                        if ($j==97) { $ans=$optionid; }
                        break;
                    case 'b':
                        if ($j==98) { $ans=$optionid; }
                        break;
                    case 'c':
                        if ($j==99) { $ans=$optionid; }
                        break;
                    case 'd':
                        if($j==100) { $ans=$optionid; }
                        break;
                }
            }
            $sql=mysqli_query($conn,"INSERT INTO answer (eid, qid, ansid) VALUES ('$eid', '$id', '$ans')");
        }
        header("location:showquiz.php");
    }
}

//delete feedback
if(isset($_GET['id'])) {
    $id=$_GET['id'];
    $sql=mysqli_query($conn,"DELETE FROM feedbacks WHERE id='$id'");
    header("location:afeedback.php");
}

//delete quiz question, option, and answer
if($_GET['q']=='deleteqns') {
    if(isset($_GET['eid'])) {
        $eid=$_GET['eid'];
        $sql=mysqli_query($conn,"SELECT * FROM question WHERE eid='$eid'");
        while($row=mysqli_fetch_array($sql)) {
            $qid=$row['id'];
            $a=mysqli_query($conn,"DELETE FROM options WHERE qid='$qid'");
            $b=mysqli_query($conn,"DELETE FROM answer WHERE qid='$qid'");
        }
        $c=mysqli_query($conn,"DELETE FROM history WHERE eid='$eid'");
        $d=mysqli_query($conn,"DELETE FROM question WHERE eid='$eid'");
        $e=mysqli_query($conn,"DELETE FROM quiz WHERE eid='$eid'");
        header("location:showquiz.php");
    }
}
?>
