<?php

require_once("db.php");
require_once("component.php");

$con = Createdb();

//create button click
if(isset($_POST['create'])){
    createData();
}

if(isset($_POST['update'])){
    UpdateData();
}

if(isset($_POST['delete'])){
    deleteRecord();
}

if(isset($_POST['deleteall'])){
    deleteAll();
}


function createData(){
    $bookname = textboxValue("book_name");
    $bookpublisher = textboxValue("book_publisher");
    $bookprice = textboxValue("book_price");

    if($bookname && $bookpublisher && $bookprice){

        $sql = "INSERT INTO books(book_name, book_publisher, book_price)
                VALUES ('$bookname', '$bookpublisher', '$bookprice')";

        if(mysqli_query($GLOBALS['con'], $sql)){
            TextNode("success", "Record Successfully Inserted!");
        }else{
            echo "Error!!";
        }

    }else{
        TextNode("error", "Please Fill the Data!");
    }
}

function textboxValue($value){
    $textbox = mysqli_real_escape_string($GLOBALS['con'], trim($_POST[$value]));
    if(empty($textbox)){
        return false;
    }else{
        return $textbox;
    }
}


//messages

function TextNode($classname, $msg){
    $element = "<h6 class='$classname'>$msg</h6>";
    echo $element;
}


//get data from mysql database
function getData(){
    $sql = "SELECT * FROM books";

    $result = mysqli_query($GLOBALS['con'], $sql);

    if(mysqli_num_rows($result)> 0){
        return $result;
    }
}

//update data
function UpdateData(){
    $bookid = textboxValue("book_id");
    $bookname = textboxValue("book_name");
    $bookpublisher = textboxValue("book_publisher");
    $bookprice = textboxValue("book_price");

    if($bookname && $bookpublisher && $bookprice){
        $sql = "UPDATE books SET book_name='$bookname', book_publisher='$bookpublisher', book_price='$bookprice' WHERE id='$bookid'";
        if (mysqli_query($GLOBALS['con'],$sql)){
            TextNode("success", "Data Successfully Updated!");
        }else{
            TextNode("error", "Unable to Update!");
        }
    }else{
        TextNode("error", "Select Data using Edit Icon");
    }

}

//delete data
function deleteRecord(){
    $bookid = (int)textboxValue("book_id");

    $sql = "DELETE FROM books WHERE id=$bookid";

    if(mysqli_query($GLOBALS['con'],$sql)){
        TextNode("success", "Record deleted successfully!");
    }else{
        TextNode("error", "Unable to delete Record!");
    }
}

function deleteBtn(){
    $result = getData();
    $i = 0;
    if($result){
        while($row = mysqli_fetch_assoc($result)){
            $i++;
            if($i>3){
                buttonElement("btn-deleteall", "btn btn-danger", "<i class='fas fa-trash'></i> Delete All","deleteall","dat-toogle='tooltip' data-placement='bottom' title='Delete All'");
                return;
            }
        }
    }
}


function deleteAll(){
    $sql= "DROP TABLE books";

    if(mysqli_query($GLOBALS['con'],$sql)){
        createdb();
        TextNode("success", "Delete All Record Successfully!");
    }else{
        TextNode("error", "Unable to Delete All Record!");
    }

}


//set id to textbox
function setID(){
    $getid = getData();
    $id = 0;
    if($getid){
        while($row = mysqli_fetch_assoc($getid)){
            $id = $row['id'];
        }
    }
    return($id + 1);
}