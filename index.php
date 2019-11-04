
<!DOCTYPE HTML>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="../ivt/style.css" type="text/css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kontaktovat</title>
</head>
<body>
<div class="center">
<div class="left">
<?php
// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Jméno není vyplněné";
    } else {
        $name = test_input($_POST["name"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
            $nameErr = "Jenom písmenka a mezery jsou povoleny";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "E-mail je vyžadován";
    } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Neplatný tvar emailu";
        }
    }

    if (empty($_POST["website"])) {
        $website = "";
    } else {
        $website = test_input($_POST["website"]);
        // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
            $websiteErr = "Neplatné URL";
        }
    }

    if (empty($_POST["comment"])) {
        $comment = "";
    } else {
        $comment = test_input($_POST["comment"]);
    }

    if (empty($_POST["gender"])) {
        $genderErr = "Pohlaví je povinné";
    } else {
        $gender = test_input($_POST["gender"]);
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<h2>PHP kontaktní formulář využívající metody POST</h2>
<p><span class="error">* povinné pole</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    Jméno: <input type="text" name="name" value="<?php echo $name;?>">
    <span class="error">* <?php echo $nameErr;?></span>
    <br><br>
    E-mail: <input type="text" name="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailErr;?></span>
    <br><br>
    Webovky: <input type="text" name="website" value="<?php echo $website;?>">
    <span class="error"><?php echo $websiteErr;?></span>
    <br><br>
    Komentář: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
    <br><br>
    Pohlaví:
    <input type="radio" name="gender" <?php if (isset($gender) && $gender=="Žena") echo "checked";?> value="Žena">Žena
    <input type="radio" name="gender" <?php if (isset($gender) && $gender=="Muž") echo "checked";?> value="Muž">Muž
    <input type="radio" name="gender" <?php if (isset($gender) && $gender=="Ostatní") echo "checked";?> value="Ostatní">Ostatní
    <span class="error">* <?php echo $genderErr;?></span>
    <br><br>
    <input class="submit" type="submit" name="submit" value="Kontaktovat">
</form>

<?php
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

if (isset($_POST['submit']) && isset($_POST['email']) && isset($_POST['name']) && isset($_POST["gender"] )){

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    mail('honza.kadlec.cb@gmail.com', 'Kontakt z webu', '<h1>Děkujeme, ze jste nas kontaktovali</h1><h2>Ozveme se hned jak to bude mozne</h2><p> vase stranka: ' . $_POST['website'] . '</p><p> vas text z webu: '. $_POST['comment'] . '<br />' . $_POST["gender"] . '<br />' . $_POST['email'] . '<br />' . get_client_ip() , $headers );
    mail($_POST['email'], 'Kontakt z webu', '<h1>Děkujeme, ze jste nas kontaktovali</h1><h2>Ozveme se hned jak to bude mozne</h2><p> vase stranka: ' . $_POST['website'] . '</p><p> vas text z webu: '. $_POST['comment'] .'</p> <p>S přáním pěkného dne<br />Jan Kadlec </p>', $headers );
    $url = 'http://www.sagasta.eu/ivt/dekujeme.html';
    header("Location: $url");
    die();
}

?>
</div>
</div>
</body>
</html>
