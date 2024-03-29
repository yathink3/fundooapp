<?php

/********************************************************************************************************************
 * @Execution : default node : cmd> FundooAccountService.php
 * @Purpose : rest api for fundoo app
 * @description: Create an Rest Api in codeigniter
 * @overview:api for login,signup,delete,passwordreset, etc
 * @author : yathin k <yathink3@gmail.com>
 * @version : 1.0
 * @since : 13-sep-2019
 *******************************************************************************************************************/

if (!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . 'rabbitmq/sender.php';
require APPPATH . 'third_party/cloudinary/Cloudinary.php';
require APPPATH . 'third_party/cloudinary/Uploader.php';
require APPPATH . 'third_party/cloudinary/Api.php';
require APPPATH . 'constants.php';
class FundooAccountService extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        //load database library
        $this->load->database();
        $this->load->library('email');
        $this->load->driver('cache', array('adapter' => 'redis', 'backup' => 'file'));
        $this->constant = new Constants();
    }

    /**
     * @param: $email
     * @method:isEmailPresent() 
     * @return :bool or result
     */
    public function isEmailPresent($email)
    {
        $stmt = $this->db->conn_id->prepare('SELECT * FROM user WHERE email=:email');
        $stmt->execute(['email' => $email]);
        if ($result = $stmt->fetch(PDO::FETCH_ASSOC)) return $result;
        else return false;
    }


    /**
     * @param: $message
     * @method:sendmail will send email 
     * @return :true or false
     */
    function sendMail($tomail, $subject, $hint, $message)
    {
        $body = '<h1>' . $hint . '</h1><p>' . $message . '</p>';
        $sender = new SendMail();
        return $sender->sendEmail($tomail, $subject, $body);
    }


    /**
     * @param:$token or payload
     * @method:jwtToken()
     * @return :boolean or data
     */
    public function jwtToken($token, $method)
    {
        if ($method)  return JWT::encode($token, $this->constant->jwt_key);
        else {
            try {
                $data = JWT::decode($token, $this->constant->jwt_key, true);
                return $data;
            } catch (Exception $e) {
                return false;
            }
        }
    }


    /**
     * @param:$userData
     * @method:signin()
     * @return :array of data
     */
    public function signin($userData)
    {
        if ($result = $this->isEmailPresent($userData['email'])) {
            if ($result['acc_status'] == TRUE) {
                if ($result['password'] === md5($userData['password'])) {
                    $this->cache->save($this->jwtToken($result['id'], true), $result);
                    return ['status' => 200, "message" => "login successful", "data" => $result];
                } else return ['status' => 503, "message" => "password mismatch"];
            } else return ['status' => 503, "message" => "validation not at done"];
        } else return ['status' => 404, "message" => "email does not exist."];
    }


    /**
     * @param:$userData
     * @method:signup()
     * @return :array of data
     */
    public function signup($userData)
    {
        if (!$this->isEmailPresent($userData['email'])) {
            $userData['password'] = md5($userData['password']);
            if (!array_key_exists('created', $userData))  $userData['created'] = date("Y-m-d H:i:s");
            if (!array_key_exists('modified', $userData))  $userData['modified'] = date("Y-m-d H:i:s");
            if ($this->sendMail($userData['email'], 'for validating email', 'please click below link to validating your account', $this->constant->frontendUrl . 'validate/?token=' . $this->jwtToken($userData['email'], true))) {
                $query = 'INSERT INTO user (firstname,lastname,email,password,created,modified) VALUES (:firstname,:lastname,:email,:password,:created,:modified)';
                if ($this->db->conn_id->prepare($query)->execute($userData))
                    return ['status' => 200, "message" => "User account has been created token generated && email sent successfully."];
                else return ['status' => 503, "message" => "Some problems occurred, please try again later"];
            } else return ['status' => 404, "message" => "token generated && email not sent."];
        } else return ['status' => 404, "message" => "email already exist."];
    }

    /**
     * @param:$userData
     * @method:sociallogin($userData)
     * @return :array of data
     */
    public function sociallogin($userData)
    {
        if ($result = $this->isEmailPresent($userData['email'])) {
            return ['status' => 200, "message" => "login successful", "data" => $result];
        } else {
            if (!array_key_exists('created', $userData))  $userData['created'] = date("Y-m-d H:i:s");
            if (!array_key_exists('modified', $userData))  $userData['modified'] = date("Y-m-d H:i:s");
            $userData['acc_status'] = TRUE;
            $query = 'INSERT INTO user (firstname,lastname,email,password,acc_status,created,modified,profilepic) VALUES (:firstname,:lastname,:email,:password,:acc_status,:created,:modified,:profilepic)';
            if ($this->db->conn_id->prepare($query)->execute($userData)) {
                if ($result = $this->isEmailPresent($userData['email'])) {
                    return ['status' => 200, "message" => "User account has been created && login successful", "data" => $result];
                }
            } else return ['status' => 503, "message" => "Some problems occurred, please try again later"];
        }
    }
    /**
     * @param:$userData
     * @method:validateAccount()
     * @return :array of data
     */
    public function validateAccount($token)
    {
        if ($email = $this->jwtToken($token, false)) {
            $data = array();
            $data['email'] = $email;
            $data['acc_status'] = TRUE;
            $data['modified'] = date("Y-m-d H:i:s");
            $query = "UPDATE user SET  modified=:modified,acc_status=:acc_status WHERE email=:email";
            $stmt = $this->db->conn_id->prepare($query);
            if ($stmt->execute($data))  return ['status' => 200, "message" => "User validation successful"];
            else return ['status' => 503, "message" => "User validation not successful"];
        } else return ['status' => 404, "message" => "unknown person"];
    }


    /**
     * @param:$userData
     * @method:forgotPassword()
     * @return :array of data
     */
    public function forgotPassword($userData)
    {
        if ($result = $this->isEmailPresent($userData['email'])) {
            if ($this->sendMail($result['email'], 'for recovering email', 'please click below link to reset your password', 'http://localhost:4200/resetPassword/?token= ' . $this->jwtToken($result['id'], true)))
                return ['status' => 200, "message" => "token generated && email sent successfully"];
            else return ['status' => 503, "message" => "token generated && email not sent"];
        } else return ['status' => 404, "message" => "wrong email address"];
    }


    /**
     * @param:$tuserData,$token
     * @method:resetPassword()
     * @return :array of data
     */
    public function resetPassword($token, $userData)
    {
        if ($id = $this->jwtToken($token, false)) {
            $userData['id'] = $id;
            $userData['password'] = md5($userData['password']);
            $userData['modified'] = date("Y-m-d H:i:s");
            $query = "UPDATE user SET  password=:password,modified=:modified WHERE id=:id";
            $stmt = $this->db->conn_id->prepare($query);
            if ($stmt->execute($userData))  return ['status' => 200, "message" => "password has been updated successfully."];
            else return ['status' => 503, "message" => "Some problems occurred, please try again."];
        } else return ['status' => 404, "message" => "unknown person"];
    }

    /**
     * @param:$userData
     * @method:uploadProfilePic($profiledata)
     * @return :array of data
     */
    public function uploadProfilePic($profiledata)
    {
        \Cloudinary::config(array(
            "cloud_name" => $this->constant->cloudinaryName,
            "api_key" => $this->constant->cloudinaryApiKey,
            "api_secret" => $this->constant->cloudinaryApiKeySecrete,
            "secure" => true
        ));
        $uploadurlData = \Cloudinary\Uploader::upload($profiledata['profilepic']);
        $profiledata['profilepic'] = $uploadurlData['url'];
        $query = "UPDATE user SET profilepic=:profilepic  WHERE id=:id";
        $stmt = $this->db->conn_id->prepare($query);
        if ($stmt->execute($profiledata)) {
            return ['status' => 200, "message" => "profile pic updated", "data" => $uploadurlData['url']];
        } else return ['status' => 503, "message" => "Some problems occurred, please try again."];
    }
}