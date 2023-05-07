<?php
require_once "inc/functions.php";

$info = ''; // common variable
$task = isset($_GET['task']) ? $_GET['task'] : 'report';
$error = isset($_GET['error']) ? $_GET['error'] : '0';

if ('delete' == $task){
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    if ($id>0){
        deleteClient($id);
        header('Location: /Store/index.php?task=report');
    }
}

if ('seed' == $task){
    seed();
    $info = "Seeding is complete";
}


$fname = '';
$lname = '';
$email = '';
$number = '';
if (isset($_POST['submit'])){
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $number = filter_input(INPUT_POST, 'number', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);

    if ($id){
        // update the existing client
        if (!empty($fname) && !empty($lname) && !empty($email) && !empty($number)){
            $result = updateClient($id, $fname, $lname, $email, $number);
            if($result){
                header('Location: /Store/index.php?task=report');
            }else{
                $error = 1;
            }
        }
    }else{
        // add a new client
        if(!empty($fname) && !empty($lname) && !empty($email) && !empty($number)){
            $result = addClient($fname, $lname, $email, $number);
            if($result){
                header('Location: /Store/index.php?task=report');
            }else{
                $error = 1;
            }
        }
    }
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Online Shop</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

</head>
<body>
    <div class="container">
        <div class="row">
            <div class="column column-80 column-offset-10">
                <h2>Online Shop</h2>
                <p>Let's work together to help small businesses sell online.</p>
                <?php include_once "inc/templates/nav.php"; ?>
                <hr/>
                <?php
                if ($info != ''){
                    echo "<blockquote><p style='color: green'>{$info}</p></blockquote>";
                }
                ?>
            </div>
        </div>

        <?php if ('1' == $error): ?>
            <div class="row">
                <div class="column column-80 column-offset-10">
                    <blockquote style="color: red;">Duplicate Email</blockquote>
                </div>
            </div>
        <?php endif; ?>

        <?php if ('report' == $task): ?>
            <div class="row">
                <div class="column column-80 column-offset-10">
                    <?php generateReport(); ?>
<!--                    <div>-->
<!--                        <pre>-->
<!--                            --><?php //printRaw(); ?>
<!--                        </pre>-->
<!--                    </div>-->
                </div>
            </div>
        <?php endif; ?>

        <?php if ('add' == $task): ?>
            <div class="row">
                <div class="column column-80 column-offset-10">
                    <form action="/Store/index.php?task=add" method="post">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" value="<?php echo $fname; ?>">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" value="<?php echo $lname; ?>">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo $email; ?>">
                        <label for="number">Number</label>
                        <input type="number" name="number" id="number" value="<?php echo $number; ?>">
                        <button type="submit" class="button-blue" name="submit">Save</button>

                    </form>
                </div>
            </div>
        <?php endif; ?>


        <?php
        if ('edit' == $task):
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            $client = getClient($id);

            if($client):
        ?>
            <div class="row">
                <div class="column column-80 column-offset-10">
                    <form method="post">
                        <input type="hidden" value="<?php echo $id; ?>" name="id">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" id="fname" value="<?php echo $client['fname']; ?>">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" id="lname" value="<?php echo $client['lname']; ?>">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo $client['email']; ?>">
                        <label for="number">Number</label>
                        <input type="number" name="number" id="number" value="<?php echo $client['number']; ?>">
                        <button type="submit" class="button-green" name="submit">Update</button>
                    </form>
                </div>
            </div>
        <?php
            endif;
        endif;
        ?>

    </div>
    <script type="text/javascript" src="assets/js/script.js"></script>
    <style>
        body{
            margin-top: 30px;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* =========== Button Small ============== */
        .button-small {
            position: relative;
            top: 5px;
            font-size: .9rem;
            height: 3rem;
            line-height: 3rem;
            padding: 0 2rem;
        }

        /* =========== Button Color [Blue] ============== */
        .button-blue {
            background-color: #0074CC;
            border-color: #0074CC;
        }
        .button-blue.button-clear,
        .button-blue.button-outline {
            background-color: transparent;
            color: #0074CC;
        }
        .button-blue.button-clear {
            border-color: transparent;
        }

        /* =========== Button Color [Red] ============== */
        .button-red {
            background-color: #EA4335;
            border-color: #EA4335;
        }
        .button-red.button-clear,
        .button-red.button-outline {
            background-color: transparent;
            color: #EA4335;
        }
        .button-red.button-clear {
            border-color: transparent;
        }

        /* =========== Button Color [Green] ============== */
        .button-green {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        .button-green.button-clear,
        .button-green.button-outline {
            background-color: transparent;
            color: #4CAF50;
        }
        .button-green.button-clear {
            border-color: transparent;
        }
    </style>
</body>
</html>