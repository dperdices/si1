<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
if(!isset($_SESSION["user"])) {
?>
<div class="title">
    Register
</div>
<?php
    if(isset($_POST["1"]) && isset($_POST["2"]) && isset($_POST["3"]) && isset($_POST["4"]) && isset($_POST["5"])) {
        /* "1" is user, "2" is pass, "3" is mail, "4" is card, "5" is expiry */
        $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb");
        if($db->query("select * from customers where username like ".$db->quote($_POST["1"]))->rowCount() == 0) {
            $db->query("insert into customers (username, password, email, creditcard, creditcardexpiration, credit) values ('".$_POST["1"]."', '".$_POST["2"]."', '".$_POST["3"]."', '".$_POST["4"]."', '".$_POST["5"]."', 0)");
            setcookie("user", $_POST["1"], time() + (2 * 60 * 60));
            echo '<div class="text">';
            echo '  Welcome to BetaBet, '.$_POST["1"].'!';
            echo '</div>';
            echo '<form method="post">';
            echo '  <button type="submit">Back</button>';
            echo '</form>';
            return;
        } else {
            $uexists = $_POST["1"];
        }
        unset($db);
    }
?>
<form method="post" name="regform" onsubmit="return false">
    User name:<br>
    <input type="text" id="user" placeholder="User name" pattern="^[a-zA-Z0-9]+$" title="Only alphanumeric user names are allowed" autofocus required><br>
    <div class="error" id="user_error"><?php if(isset($uexists)) echo 'User '.$uexists.' already exists.';?></div>
    <br>Password:<br>
    <input type="password" id="pass" placeholder="Password" oninput="passwordStrength(this.value)" required><br>
    <input type="password" id="passrep" placeholder="Repeat password" required><br>
    <div id="strength" class="strength">Password Strength</div>
    <div class="error" id="pass_error"></div>
    <br>E-mail address:<br>
    <input type="email" id="mail" placeholder="E-mail address" required><br>
    <div class="error" id="mail_error"></div>
    <br>Credit Card information:<br>
    <span class="cardinfo">
        <input type="text" id="card" placeholder="Credit Card No." pattern="^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$" title="Supported cards: Visa, MasterCard, American Express, Diners Club, Discover and JCB" required>
        <input type="text" id="exp" placeholder="Exp. Date" pattern="^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$" title="Format: mm/yy or mm/yyyy" required><br>
    </span>
    <div class="error" id="card_error"></div>
    <input type="reset" id="clear" value="Clear" onclick="passwordStrength()">
    <input type="submit" id="register" value="Register" onclick="validateRegister()">
</form><br>
<?php
}
?>
