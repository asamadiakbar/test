<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
set_time_limit(0);
use \Magento\Framework\App\Bootstrap;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);
$shippmentCollection = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\Shipment\Collection');
$shippmentCollection->addAttributeToSelect('*');

$success_count = 0;
$failed_count = 0;
    foreach ($shippmentCollection as $shipment) {
        $created_at = $shipment->getCreatedAt();
        $date_shippment = date('d-m-Y', strtotime($created_at));
            
        //date_default_timezone_set('Asia/Calcutta');
        $date_modify = (new \DateTime())->modify('-0 day');
        $date = $date_modify->format('d-m-Y');           

        if($date == $date_shippment)
        {   
            $email = $shipment->getShippingAddress()->getEmail();
            getSendEmail($email);
            $success_count++; 
        }
        else
        {
            $failed_count++;
        }       
}

if($success_count > $failed_count){
    echo "final output is success send Mail";
}else{
   echo "final output is failed Date not Match";
}

function getSendEmail($email)
    {        
        $mail = new PHPMailer(true); 

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'test.23digital@gmail.com';
        $mail->Password = 'hbpfnojgywbqerro';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    
        $mail->setFrom('test7.23digital@gmail.com','Admin');
        $mail->addAddress($email);

        //Content
        $mail->isHTML(true); 
        $mail->Subject = 'Mail Subject Here!';
        $mail->Body    = '<b>Mail body content goes here</b>';
    
        $mail->send();
            
        return;        
        } 
   
?>   