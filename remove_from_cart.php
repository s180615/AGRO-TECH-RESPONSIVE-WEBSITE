<?php
if (isset($_POST['remove'], $_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Find the item in the cart and remove it
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] == $product_id) {
                unset($_SESSION['cart'][$key]);
                break;
            }
        }
    }

    // Reset array keys to maintain continuous indexing
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}
?>
