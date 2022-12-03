<?php
require('config.php'); 

	$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
				=== 'on' ? "https" : "http") .
				"://" . $_SERVER['HTTP_HOST'] .
				$_SERVER['REQUEST_URI'];
				
if(isset($_POST['track_order']))                                    
{ 

    $order_id = $_POST['order_id'];    
    
    $sqlz= "SELECT * FROM orders WHERE order_id = '$order_id'"; 
    $stmt = $db->query($sqlz); 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $order_status = $row['order_status'];
    $order_date = $row['order_date'];    
    $order_download = $row['order_decoded'];
    
    $order_info = 1;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex, nofollow">

    <title>Order Track ionCube Decode | Demo Version</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <style type="text/css">
    @import "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css";

.shop-tracking-status .form-horizontal{margin-bottom:50px}
.shop-tracking-status .order-status{margin-top:150px;position:relative;margin-bottom:150px}
.shop-tracking-status .order-status-timeline{height:12px;border:1px solid #aaa;border-radius:7px;background:#eee;box-shadow:0px 0px 5px 0px #C2C2C2 inset}.shop-tracking-status .order-status-timeline .order-status-timeline-completion{height:8px;margin:1px;border-radius:7px;background:#cf7400;width:0px}
.shop-tracking-status .order-status-timeline .order-status-timeline-completion.c1{width:0%}
.shop-tracking-status .order-status-timeline .order-status-timeline-completion.c2{width:50%}
.shop-tracking-status .order-status-timeline .order-status-timeline-completion.c3{width:100%}
.shop-tracking-status .image-order-status{border:1px solid #ddd;padding:7px;box-shadow:0px 0px 10px 0px #999;background-color:#fdfdfd;position:absolute;margin-top:-35px}.shop-tracking-status .image-order-status.disabled{filter:url("data:image/svg+xml;utf8,<svg%20xmlns='http://www.w3.org/2000/svg'><filter%20id='grayscale'><feColorMatrix%20type='matrix'%20values='0.3333%200.3333%200.3333%200%200%200.3333%200.3333%200.3333%200%200%200.3333%200.3333%200.3333%200%200%200%200%200%201%200'/></filter></svg>#grayscale");filter:grayscale(100%);-webkit-filter:grayscale(100%);-moz-filter:grayscale(100%);-ms-filter:grayscale(100%);-o-filter:grayscale(100%);filter:gray;}
.shop-tracking-status .image-order-status.active{box-shadow:0px 0px 10px 0px #cf7400}.shop-tracking-status .image-order-status.active .status{color:#cf7400;text-shadow:0px 0px 1px #777}
.shop-tracking-status .image-order-status .icon{height:40px;width:40px;background-size:contain;background-position:no-repeat}
.shop-tracking-status .image-order-status .status{position:absolute;text-shadow:1px 1px #eee;color:#333;transform:rotate(-30deg);-webkit-transform:rotate(-30deg);width:180px;top:-50px;left:50px}.shop-tracking-status .image-order-status .status:before{font-family:FontAwesome;content:"\f053";padding-right:5px}
.shop-tracking-status .image-order-status-new{left:0}.shop-tracking-status .image-order-status-new .icon{background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAA+JJREFUWIXtll+I1FUUxz/n3vv7/WZmZ/+OphuWlliQZn8f2igxyCSIRMgeFNrUDVlUCIoeit4KeujN1CxZN5HAkiCo6CUq0NRU1KUgoqhlFcvd1f0z7Tgzv9+9PfyG2dl1Vpsa8KH9wuHec+/9nfu9557fORdmMYv/O+Rak70vpJcaP3gzDPNrnLg8DjvlS4cCgupGBS1yxFl56bn3xo7XRGDflqYlfpA6qJS+c/mja1LNbe1EUYggGD8A5aNMAtEBykyK6ATKC1A6ILTO/fB1j/Sf+ugvG3HRin2lc1f20HUJ7O9qvk0S/ollKzpb5y26Q40MnHVnD38mTgDncFEeowSjwdOC0YKnwUzrJ5ItZB57w104vteFg6fVz+dGc0XLto3vjvdU7qcrlQNdyQUkk8fvXd3dtviB1Xr8Qh+nv/lYCoUr2CjE2ggloERQCrQSlBK0AjWtLy5P4fJPclPHi5L/4xStidAbHsuvevpBM/DpyWLfVR54v6thXjKVPLls5ab29iUd2o39ihc0UIwmnWSdBWtBGUR7sahYKLWiDYguG042z6eQG6evdy2iNL9dGJtw1m7q3JM9WCbQszE9N2hMHWtbsHShOHJDA33pR555jea5t5IbOTd5X6JANIiKBQ1K4g1FynMSBygAQUOGyMGXO9bjbCGXDPxENpcPcazv3JM9JHs3N7UZY4cB/CB1Pizmv7v9vifX3bN6G9HIL+AszllwLrbqXKyX+v9kzM/cRXbkIl998PJFXHgmsvYJALFsVcZEbwOgZM2VYn5F0Jh5avmqrUSjv2OLE9hiDhdeKYsNp+nF3HXH8n+epXHOIu5euaFFtB62Si8WOOoUO6f8BQe2zzn8+ObdD6czC6+ZH/4Lvt2/PTd0/seNnbsqYgBgX3fTQ2LtUaV1UUR5ojTORjWYdjPOiMS2HC5voygAeH5PVqYQcCAfdje3ABRtdKlj7asMHOupbrFG3Hz/Or7/fAee0m0AnvjFZ3cNZgFMmSU4do9eBujdkiaRSpNMpetCIEg2ArChZL8S5qrVJSht8JMNdSGgjTfj3IwEtPHwE/XxgDZ+7QSM9ggS9fGA+fceuJEEPI+gTjFg/PIV+EDhWgQCoBXiimf8FLhackEViEKpctG9BRgDLgFRNQLNQMYpb3C4/8zczMKO0nBFknGVCceVdVd1TdwO9fdhxRsC5pf2jEokphDQJd374vTEW9idrwthS43nrQqHGfnkxMQ7QALwqHiHTM/5rUCm1AbEd1YPFAAFZIlPfh4oViMAkARaSpvPGKQ1IiS+j1FgHCYft9erevWqijNXqlncaPwNorOPpsGntkIAAAAASUVORK5CYII=);}
.shop-tracking-status .image-order-status-active{left:50%}.shop-tracking-status .image-order-status-active .icon{background-image: url(data:image/png;base64,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);}
.shop-tracking-status .image-order-status-completed{right:0px}.shop-tracking-status .image-order-status-completed .icon{background-image: url(data:image/png;base64,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);}
.shop-tracking-status .image-order-status-completed .status{top:85px;left:-180px;transform:rotate(-30deg);-webkit-transform:rotate(-30deg);text-align:right}.shop-tracking-status .image-order-status-completed .status:before{display:none}
.shop-tracking-status .image-order-status-completed .status:after{font-family:FontAwesome;content:"\f054";padding-left:5px;vertical-align:middle}
    </style>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        window.alert = function(){};
        var defaultCSS = document.getElementById('bootstrap-css');
        function changeCSS(css){
            if(css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="'+ css +'" type="text/css" />'); 
            else $('head > link').filter(':first').replaceWith(defaultCSS); 
        }
    </script>
</head>
<body>
    <div class="row shop-tracking-status">
    
    <div class="col-md-12">
        <div class="well">
    <form action="#" method="post">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="inputOrderTrackingID" class="col-sm-2 control-label">Order id</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" name="order_id" id="inputOrderTrackingID" value="" placeholder="# put your order id here">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" name="track_order" id="shopGetOrderStatusID" class="btn btn-success">Get status</button>
                    </div>
                </div>
            </div>
    </form>
    <?php if ($order_info == 1) { ?>
            <h4>Your order status:</h4>

            <ul class="list-group">
                <li class="list-group-item">
                    <span class="prefix">Date created:</span>
                    <span class="label label-success"><?=$order_date?></span>
                </li>
                <li class="list-group-item">
                    <span class="prefix">Download Link:</span>
                    <?php if ($order_status == 2) { ?>
                    <span class="label label-success"><a href="<?=$link.'decode_files/'.$order_download?>">Click here</a></span> <?php } else { ?>
                    <span class="label label-danger">Not available!</span>    
                    <?php } ?>
                </li>
            </ul>

            <div class="order-status">

                <div class="order-status-timeline">
                    <?php if ($order_status == 0) { ?>
                    <div class="order-status-timeline-completion c1"></div>
                    <?php } elseif ($order_status == 1) { ?>
                    <div class="order-status-timeline-completion c2"></div>                        
                    <?php } elseif ($order_status == 2) { ?>
                    <div class="order-status-timeline-completion c3"></div>                        
                    <?php } ?>
                </div>

                <div class="image-order-status image-order-status-new active img-circle">
                    <span class="status">Accepted</span>
                    <div class="icon"></div>
                </div>
                <div class="image-order-status image-order-status-active active img-circle">
                    <span class="status">In progress</span>
                    <div class="icon"></div>
                </div>
                <div class="image-order-status image-order-status-completed active img-circle">
                    <span class="status">Completed</span>
                    <div class="icon"></div>
                </div>

            </div>
            <?php } ?>
        </div>
    </div>

</div>	<script type="text/javascript">
		</script>
</body>
</html>
