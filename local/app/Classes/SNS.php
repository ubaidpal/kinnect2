<?php

namespace App\Classes;

use AWS;
use App\SnsEndpoint;

class SNS
{

    public function getEndpointProtocol($data){  //TODO: add required platform protocols for dev/production environments
        $protocols["iOS"] = env('APPLE_SNS_PROTOCOL', "APNS_SANDBOX");
        return $protocols[$data];
    }

    public function createPlatformEndpoint($user_i = 1, $token, $userData, $platform = "iOS", $attributes = array()){
        $client = AWS::createClient('sns');
        if($platform == "iOS"){
            $platformARN =  env('AWS_SNS_IOS_APPLICATION_ARN');
        }else if($platform == "android"){
            $platformARN =  env('AWS_SNS_ANDROID_APPLICATION_ARN');
        }
        if(isset($platformARN) && $platformARN){

            $endPoint = SnsEndpoint::where( 'device_token', $token)
                ->select( [ "id",'arn'] )
                ->first();
            if($endPoint){
                $this->deleteEndpoint($endPoint);

            }

            $result = $client->createPlatformEndpoint(array(
                'PlatformApplicationArn' => $platformARN,
                'Token' => $token,
                'CustomUserData' => $userData,
                'Attributes' => $attributes
            ));
            $result["EndpointArn"];
            $data["arn"] = $result["EndpointArn"];
            $data["user_id"] = $user_i;
            $data["platform"] = $platform;
            $data["device_token"] = $token;
            $this->saveSnsEndpoint($data);
        }else{
            return false;
        }
    }

    public function saveSnsEndpoint($data){
        $endPoint = SnsEndpoint::where('user_id', $data['user_id'])
                    ->where( 'arn', $data['arn'] )
                    ->select( [ 'id'] )
                    ->first();
        if(!$endPoint){
            $endPoint = new SnsEndpoint();
            $endPoint->user_id     = $data['user_id'];
            $endPoint->arn       = $data['arn'];
            $endPoint->platform             = $data['platform'];
            $endPoint->device_token = $data['device_token'];
            if ( $endPoint->save()){
                return $endPoint->id;
            }
        }
    }

    public function deleteEndpoint($endpoint){
        $client = AWS::createClient('sns');
        $client->deleteEndpoint([
            'EndpointArn' => $endpoint->arn
        ]);
        $endpoint->delete();
    }

    public function _sendPushNotification($endpointArn, $data){
        //print_r($data); die("dds");
        //print_r($data);
//        $this->getEndpointProtocol( $data["platform"]);
//        echo $endpointArn."<br/><pre>";
//        var_dump($data);
//        die("dddd");

        $client = AWS::createClient('sns');

        $dataOptions = array(
            'aps' => array(
                'alert' => $data["title"],
                'sound' => 'default',
                'badge' => 1
            )
        );

        foreach ($data["data"] as $ind => $val) {
            $dataOptions[$ind] = $val;
        }

        try{
            $client->setEndpointAttributes(
                array(
                    'Attributes' => array('Enabled' => 'True'), // REQUIRED
                    'EndpointArn' => $endpointArn // REQUIRED
                )
            );
        }catch (Exception $e)
        {
            return $e;
        }

        if($data["platform"] == "iOS"){
            try{
                $client->publish(array(
                    'TargetArn' => $endpointArn,
                    'MessageStructure' => 'json',
                    'Message' => json_encode(array(
                        'default' => $data["title"],
                        $this->getEndpointProtocol($data["platform"]) => json_encode($dataOptions)
                    ))
                ));
            }
            catch (Exception $e){
                return $e;
            }

        }else{
            try{
                $client->publish(array('Message' => json_encode($dataOptions),
                'TargetArn' => $endpointArn));
            }catch (Exception $e){
                return $e;
            }

        }
        return true;
    }

    public function sendPushNotification($userId, $data){
        $endPoints = SnsEndpoint::where('user_id', $userId)
            ->select( [ 'arn', 'platform'] )
            ->get();

        foreach($endPoints as $endPoint){
            if($endPoint->arn){
                $data["platform"] = $endPoint->platform;
                $this->_sendPushNotification($endPoint->arn, $data);
            }
        }
        return true;


//        $result = $client->publish(array(
//            'TargetArn' => 'arn:aws:sns:eu-central-1:988999140519:endpoint/APNS_SANDBOX/k2iOS-Dev/5774646a-e053-3d72-9ff6-19b35f30515d',
//            // Message is required
//            'Message' => 'Hello SNS',
//            'Subject' => 'Hello SNS+Laravel API User',
//            'MessageStructure' => 'string',
//            'MessageAttributes' => array(
//                // Associative array of custom 'String' key names
//                'String' => array(
//                    // DataType is required
//                    'DataType' => 'string',
//                    'StringValue' => 'string',
//                    'BinaryValue' => 'string',
//                ),
//                // ... repeated
//            ),
//        ));
    }

    public function deletePlatformEndpoint(){
        // implementation
    }

}