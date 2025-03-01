<?php
class UserView {
    public static function render($data) {
        header("content-Type: application/json");
        echo json_encode($data);
    }
}
?>