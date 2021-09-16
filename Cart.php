<?php
include "lib.inc.php";
$smarty = includesmarty();
checkSessione();

$id_logged = $_SESSION["id"];

$email = $_SESSION["user"];
$sql = "SELECT * FROM user WHERE email='$email' ";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_array($result);
$role = $row['role_id'];
if ($role == 1) {
    $error = "Registrati come cliente prima di comprare";
    header("Location:Error.php?msg=" . $error);
} else {

//prendo l'id del utente loggato
    $sql_shopping_cart = "SELECT S_I.id,book.pic,book.title,book.price,S_I.item_quantity,book.quantity,S_I.book_id,S_I.subtotal
FROM book INNER JOIN shopping_cart_item as S_I ON book.id=S_I.book_id INNER JOIN shopping_cart AS S_C ON S_C.customer_id=" . $id_logged;
//faccio il join con le due tabelle
    $res = mysqli_query($con, $sql_shopping_cart);
//eseguo la query sul db
    $resrow = array();
    while ($resrow = mysqli_fetch_array($res)) {
        //scorro l'array della result
        $carts[] = $resrow;
//lo metto in un array
        $smarty->assign("carts", $carts);
    }


    $total_sql = "SELECT ROUND(SUM(subtotal*item_quantity),2) FROM shopping_cart_item RIGHT JOIN shopping_cart ON customer_id=" . $id_logged;
    $total_res = mysqli_query($con, $total_sql);
    $total = mysqli_fetch_assoc($total_res);
    $tot = $total["ROUND(SUM(subtotal*item_quantity),2)"];


    $smarty->assign("total", $tot);
    $smarty->display("Cart.tpl");

}






