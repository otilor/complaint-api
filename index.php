<?php
include ('config/db_connect.php');
// $response_type = "json";
$response_type = strtolower($_GET["response_type"]);



 
/**
 * data function
 * 
 * @var $fetch_sql
 * @var $fetch_query
 * @var $fetch_result
 * @var $total_complaints
 * @return array $data | string "Failed"
 */
function data($conn)
{
    // Test to the database
    $conn = conn($conn);
    // SQL to fetch all the complaints in the database
    $fetch_sql = "SELECT * FROM complaints";
    // Query the database
    $fetch_query = mysqli_query($conn, $fetch_sql);
    // Resolve the result into an Associative Array
    $fetch_result = mysqli_fetch_all($fetch_query, MYSQLI_ASSOC);
    // Complaints that have been accepted
    $accepted_complaints = [];
    // All the complaints
    $total_complaints = count($fetch_result);
    // Messages
    $messages = [];

    // Filter Accepted Messages into an array
    foreach ($fetch_result as $result)
    {
        if ($result["accepted?"] == 1)
        {
            array_push($accepted_complaints, $result);
        }
    }

    // All the messages go here
    foreach ($fetch_result as $message)
    {
        array_push ($messages, $message["message"]);
    }
    
    
    if (!is_null($conn))
    {
        
        
        $data = [
            "messages" => $messages,              
            "status" => 200,
            "totalComplaints" => count($fetch_result),
            "acceptedComplaints" => count($accepted_complaints),
        ];
    
        return $data;
    }
    else
    {
        return "Failed";
    }
    
    
}

/**
 * Convert Array to XML
 * 
 * @param  array $array
 * @param bool $xml
 * @return string $xml
 */
function array2xml($array, $xml=false)
{
    if ($xml === false)
    {
        $xml = new SimpleXMLElement('<result/>');
    }
    foreach ($array as $key => $value)
    {
        if (is_array($value))
        {
            array2xml($value, $xml->addChild($key));
        }
        else{
            $xml->addChild($key, $value);
        }
        
    }
    return $xml->asXML();
}


// Checks for the specified respone type
if($response_type == "json")
{
    header("Content-Type: application/json");
    $data= data(conn());
    echo json_encode($data);
}
elseif ($response_type == "xml")
{
    header('Content-Type: text/xml');
    $data = data(conn());
    $xml = array2xml($data, false);
    print_r($xml);
}

else {
    //header($_SERVER["SERVER_PROTOCOL"]. "500 Internal server error");
    http_response_code(400);
}