<?php

$host = "localhost";
$username = "store_admin";
$password = "password1#";
$database = "store_data";

$conn = mysqli_connect($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully" . "<br/>";
}


$check = "CREATE TABLE IF NOT EXISTS starwarstable (
            ID int NOT NULL AUTO_INCREMENT,
            Name VARCHAR(1000),
            rotation_period int,
            orbital_period int,
            diameter int,
            climate text,
            gravity text,
            terrain text,
            surface_water int,
            population bigint,
            created datetime,
            edited datetime,
            url VARCHAR(1000),  
            PRIMARY KEY (ID)
        )";

if ($conn->query($check) === TRUE) {
    echo "starwarstable created successfully and echoed as well.<br>";
} else {
    echo "Error creating table: " . $conn->error;
}



$curl = curl_init('https://swapi.dev/api/planets/');

if (!$curl) {
    die("Couldn't initialize a cURL handle");
}


curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


$result = curl_exec($curl);


if (curl_errno($curl)) {
    echo (curl_error($curl));
    die();
}


curl_close($curl);


$response_data = json_decode($result, true);


echo('<pre>');
print_r($response_data);
echo('</pre>');
echo('<br>');


foreach ($response_data['results'] as $planet) {
    $query = "INSERT INTO starwarstable (name, rotation_period, orbital_period, diameter, 
    climate, gravity, terrain, surface_water, population, created, edited, url)
              VALUES (
                '" . $conn->real_escape_string($planet['name']) . "',
                '" . $conn->real_escape_string($planet['rotation_period']) . "',
                '" . $conn->real_escape_string($planet['orbital_period']) . "',
                '" . $conn->real_escape_string($planet['diameter']) . "',
                '" . $conn->real_escape_string($planet['climate']) . "',
                '" . $conn->real_escape_string($planet['gravity']) . "',
                '" . $conn->real_escape_string($planet['terrain']) . "',
                '" . $conn->real_escape_string($planet['surface_water']) . "',
                '" . $conn->real_escape_string($planet['population']) . "',
                '" . $conn->real_escape_string($planet['created']) . "',
                '" . $conn->real_escape_string($planet['edited']) . "',
                '" . $conn->real_escape_string($planet['url']) . "'
            )";

    $conn->query($query);

    if ($conn->errno) {
        echo($conn->error);
        die();
    }
}

echo "Data inserted successfully!";


$conn->close();

?>