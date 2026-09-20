<?php

include("../database/config.php");


$new_password = "Admin@12345";

$hash = password_hash($new_password, PASSWORD_DEFAULT);


$query = "UPDATE subadmins 
SET password='$hash'
WHERE email='subadmin@gmail.com'";


if(mysqli_query($conn,$query)){

    echo "Password Updated Successfully";

    echo "<br>New Password: ".$new_password;

}
else{

    echo "Error: ".mysqli_error($conn);

}

?>