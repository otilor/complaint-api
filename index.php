<?php
include ('config/db_connect.php');
$response_type = "json";


 
/**
 * data function
 */
function data($conn)
{
    $conn = conn($conn);
    $fetch_sql = "SELECT * FROM complaints";
    $fetch_query = mysqli_query($conn, $fetch_sql);
    $fetch_result = mysqli_fetch_all($fetch_query, MYSQLI_ASSOC);
    $accepted_complaints = [];
    $total_complaints = count($fetch_result);
    $messages = [];

    foreach ($fetch_result as $result)
    {
        if ($result["accepted?"] == 1)
        {
            array_push($accepted_complaints, $result);
        }
    }

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



if($response_type != "xml")
{
    header("Content-Type: application/json");
    $data= data(conn());
    echo json_encode($data);
}
else
{
    header('Content-Type: text/xml');
    $data = data(conn());
    $xml = array2xml($data, false);
    print_r($xml);
}