<?php

define('DB_NAME', 'C:\xampp\htdocs\PHP_Laravel(Ostad)\Advance\Store\data\db.txt');
function seed(){
    $data = array(
        array(
            'id' => 1,
            'fname' => 'Mainur',
            'lname' => 'Rahaman',
            'email' => 'mainur455@gmail.com',
            'number' => '01881123454'
        ),
        array(
            'id' => 2,
            'fname' => 'Miraj',
            'lname' => 'Hossain',
            'email' => 'miraj546@gmail.com',
            'number' => '01881123454'
        ),
        array(
            'id' => 3,
            'fname' => 'Siam',
            'lname' => 'Ahmed',
            'email' => 'siam657@gmail.com',
            'number' => '01881123454'
        ),
        array(
            'id' => 4,
            'fname' => 'Ariful',
            'lname' => 'Islam',
            'email' => 'ariful43@gmail.com',
            'number' => '01881123454'
        ),
    );

    $serializedData = serialize($data);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}


function generateReport(){
    $serializedData = file_get_contents(DB_NAME);
    $clients = unserialize($serializedData); ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <?php foreach ($clients as $client): ?>
        <tbody>
            <tr>
                <td><?php printf('%s', $client['id']); ?></td>
                <td><?php printf('%s %s', $client['fname'], $client['lname']); ?></td>
                <td><?php printf('<a style="color: darkslategray" href="#">%s</a>', $client['email']); ?></td>
                <td><?php printf('%s', $client['number']); ?></td>
                <td>
                    <?php
                    printf('<a class="button button-blue button-small" href="/Store/index.php?task=edit&id=%s">Edit</a> <a class="delete button button-red button-small" href="/Store/index.php?task=delete&id=%s">Delete</a>', $client['id'], $client['id']);
                    ?>
                </td>
            </tr>
        </tbody>
        <?php endforeach; ?>
    </table>



<?php
}

function addClient($fname, $lname, $email, $number){
    $found = false;

    $serializedData = file_get_contents(DB_NAME);
    $clients = unserialize($serializedData);

    foreach($clients as $_client){
        if($_client['email'] == $email){
            $found = true;
            break;
        }
    }

    if(!$found){
        $newId = getNewId($clients);
        $client = array(
            'id' => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'number' => $number
        );
    
        array_push($clients, $client);
        $serializedData = serialize($clients);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}


function getClient($id){
    $serializedData = file_get_contents(DB_NAME);
    $clients = unserialize($serializedData);

    foreach($clients as $client){
        if($client['id'] == $id){
            return $client;
        }
    }
    return false;
}

function updateClient($id, $fname, $lname, $email, $number){
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $clients = unserialize($serializedData);

    foreach($clients as $_client){
        if($_client['email'] == $email && $_client['id'] != $id){
            $found = true;
            break;
        }
    }

    if (!$found){
        $clients[$id-1]['fname'] = $fname;
        $clients[$id-1]['lname'] = $lname;
        $clients[$id-1]['email'] = $email;
        $clients[$id-1]['number'] = $number;

        $serializedData = serialize($clients);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}

function deleteClient($id){
    $serializedData = file_get_contents(DB_NAME);
    $clients = unserialize($serializedData);

    foreach($clients as $offset=>$client){
        if($client['id'] == $id){
            unset($clients[$offset]);
        }
    }

    $serializedData = serialize($clients);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}

//function printRaw(){
//    $serializedData = file_get_contents(DB_NAME);
//    $clients = unserialize($serializedData);
//
//    print_r($clients);
//}

function getNewId($clients){
    $maxId = max(array_column($clients, 'id'));
    return $maxId+1;
}

?>
