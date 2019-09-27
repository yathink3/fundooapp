<?php

/********************************************************************************************************************
 * @Execution : default node : cmd> FundooNotes.php
 * @Purpose : rest api for fundoo app for notesdata
 * @description: Create an Rest Api in codeigniter
 * @overview:api for create,delete,getall,update,reminder etc
 * @author : yathin k <yathink3@gmail.com>
 * @version : 1.0
 * @since : 13-aug-2019
 *******************************************************************************************************************/

require APPPATH . 'services/FundooNotesService.php';
class FundooNotes
{
    public function __construct()
    {
        $this->services = new FundooNotesService();
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
    }


    /**
     * @param:null
     * @method:createNote()
     * @return :response 
     */
    public function createNote()
    {
        $data = json_decode(file_get_contents("php://input"));
        $notesData = array();
        $notesData['userid'] = $data->userid;
        $notesData['title'] = $data->title;
        $notesData['desc'] = $data->desc;
        $notesData['rem'] = $data->rem;
        $notesData['isArchieve'] = $data->isArchieve;
        $notesData['colorid'] = $data->colorid;
        $notesData['labelid'] = $data->labelid;
        $responce = $this->services->createNote($notesData);
        http_response_code($responce['status']);
        echo json_encode($responce);
        // $this->getAllNotes($data->userid);
    }


    /**
     * @param:null
     * @method:getAllNotes()
     * @return :response 
     */
    public function getAllNotes($userid)
    {
        // $token = isset($_GET['token']) ? $_GET['token'] : die();
        $responce = $this->services->getAllNotes($userid);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }


    /**
     * @param:$id
     * @method:getOneNotes()
     * @return :response 
     */
    public function update($id)
    {
        // $token = isset($_GET['token']) ? $_GET['token'] : die();
        $responce = $this->services->getOneNote($id);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }
    public function updateNotecolor()
    {
        $data = json_decode(file_get_contents("php://input"));
        $notesData = array();
        $notesData['noteid'] = $data->note_id;
        $notesData['colorid'] = $data->color_id;
        $responce = $this->services->updateNotecolor($notesData);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }
    public function updateNoteReminder()
    {
        $data = json_decode(file_get_contents("php://input"));
        $notesData = array();
        $notesData['noteid'] = $data->note_id;
        $notesData['reminder'] = $data->reminder;
        $responce = $this->services->updateNoteReminder($notesData);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }
    public function  archievenoteSet()
    {
        $data = json_decode(file_get_contents("php://input"));
        $notesData = array();
        $notesData['noteid'] = $data->note_id;
        $notesData['isArchieve'] = $data->isArchieve;
        $responce = $this->services->archievenoteSet($notesData);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }
    public function addTrashnote()
    {
        $data = json_decode(file_get_contents("php://input"));
        $notesData = array();
        $notesData['noteid'] = $data->note_id;
        $notesData['isTrash'] = $data->isTrash;
        $responce = $this->services->addTrashnote($notesData);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }











    /**
     * @param:$id
     * @method:updateNote()
     * @return :response 
     */
    public function updateNote($id)
    {
        $data = json_decode(file_get_contents("php://input"));
        $notesData = array();
        $notesData['userid'] = $data->userid;
        $notesData['title'] = $data->title;
        $notesData['desc'] = $data->desc;
        $notesData['rem'] = $data->rem;
        $notesData['color'] = $data->color;
        $notesData['labelid'] = $data->labelid;
        $notesData['image'] = $data->image;
        $responce = $this->services->updateNote($notesData, $id);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }


    /**
     * @param:$id
     * @method:deleteNote()
     * @return :response 
     */
    public function deleteNote($id)
    {
        // $token = isset($_GET['token']) ? $_GET['token'] : die();
        $responce = $this->services->deleteNote($id);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }


    /**
     * @param:$id
     * @method:trashSet()
     * @return :response 
     */
    public function trashSet($id)
    {
        // $token = isset($_GET['token']) ? $_GET['token'] : die();
        $responce = $this->services->trashSet($id);
        http_response_code($responce['status']);
        echo json_encode($responce);
    }
}