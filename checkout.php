<?php
include "lib.inc.php";
$smarty = includesmarty();
checkSessione();
$id_logged = $_SESSION["id"];

$check_cart = "SELECT * FROM `shopping_cart_item` INNER JOIN shopping_cart ON shopping_cart.id=shopping_cart_item.shopping_cart_id 
    INNER JOIN user ON shopping_cart.customer_id=" . $id_logged;
$check_row = mysqli_query($con, $check_cart);
$check = mysqli_fetch_row($check_row);

if ($check == 0) {
    $error = "Non puoi comprare senza prodotti, inserisci prima dei prodotti nel tuo carrello!";
    header("Location:Error.php?msg=" . $error);


}else{

//prendo l'id del utente loggato
    $sql_shopping_cart = "SELECT * FROM book INNER JOIN shopping_cart_item as S_I ON book.id=S_I.book_id INNER JOIN shopping_cart AS S_C ON S_C.customer_id=" . $id_logged;;
//faccio il join con le due tabelle
    $res = mysqli_query($con, $sql_shopping_cart);
//eseguo la query sul db
    $resrow = array();
    while ($resrow = mysqli_fetch_array($res)) {
        $data[] = $resrow;


    }


    $total_sql = "SELECT ROUND(SUM(subtotal*item_quantity),2) FROM shopping_cart_item RIGHT JOIN shopping_cart ON customer_id=" . $id_logged;
    $total_res = mysqli_query($con, $total_sql);
    $total = mysqli_fetch_assoc($total_res);
    $tot = $total["ROUND(SUM(subtotal*item_quantity),2)"] + 8;

    $sql = "SELECT * FROM customer_address WHERE customer_id=" . $id_logged;
    $result = mysqli_query($con, $sql);
    $address = array();
    while ($address = mysqli_fetch_array($result)) {
        $addresses[] = $address;
        $smarty->assign("addresses", $addresses);

    }


    $smarty->assign("books", $data);
    $smarty->assign("total", $tot);
    $smarty->display("Checkout.tpl");
}
