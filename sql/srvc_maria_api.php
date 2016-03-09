<?php
    
function srvc_maria_setup()
{
    $DB_NAME = "DB_TOO_STUDIO";
    
    $con = mysql_connect("localhost", "root", "");
    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }

    // Create database
    if (mysql_query("CREATE DATABASE $DB_NAME", $con))
    {
    }
    else
    {
        echo "Error creating database: " . mysql_error();
    }

    // Create table in my_db database
    mysql_select_db("$DB_NAME", $con);
    $sql = "CREATE TABLE TICKETS 
    (
    TID int NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(TID),
    PHONE varchar(20),
    COUNT int,
    NAME varchar(20)
    )";
    mysql_query($sql, $con);

    mysql_close($con);
}
    
srvc_maria_setup();

?>