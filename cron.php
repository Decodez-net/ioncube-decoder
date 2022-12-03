<?php
require ('config.php');
require ('api.php');

$api = new Api();

$sqlz = "SELECT * FROM orders WHERE order_status = '0' or order_status = '1' ORDER BY id DESC";
$stmtz = $db->query($sqlz);
while ($rowz = $stmtz->fetch(PDO::FETCH_BOTH))
{

    $order_id = $rowz['order_id'];
    $order_api_id = $rowz['order_api_id'];

    $status_order = $api->status($order_api_id);

    if ($status_order->status == 'processing')
    {

        $sql_update = "UPDATE orders SET order_status = '1' WHERE order_api_id = '$order_api_id'";
        $smt_update = $db->prepare($sql_update);
        $smt_update->execute();
        echo "Successfully";
    }
    elseif ($status_order->status == 'completed')
    {

        $destination_dir = 'decode_files/';
        $local_zip_file = $order_id . "_decode.zip";
        if (!copy($status_order->download, $destination_dir . $local_zip_file))
        {
            $data = array(
                'status' => "error",
                'message' => "File not Uploaded our Site",
            );
            $json = json_encode($data);
            header('Content-Type: application/json');
            echo $json;
            exit;
        }
        $sql_update = "UPDATE orders SET order_status = '2', order_decoded = '$local_zip_file' WHERE order_api_id = '$order_api_id'";
        $smt_update = $db->prepare($sql_update);
        $smt_update->execute();
        echo "Successfully";
    }

}

