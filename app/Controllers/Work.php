<?php
namespace Todolist\Controllers; 

class Work
{
    /**
     * PAGE: index
     * http://todolist.biz/work/index
     */
    public function index()
    {
        require 'app/views/_templates/header.php';
        require 'app/views/work/index.php';
        require 'app/views/_templates/footer.php';
    }
    
    /**
     * ACTION: listWork
     * http://todolist.biz/work/listwork
     */
    public function listWork()
    {
        $config     = new \Todolist\Config('');
        $workModel  = $config->loadModel('Work');
        $work       = $workModel->getAllWork();
        require 'app/views/work/listwork.php';
    }
    
    /**
     * ACTION: addWork
     * http://todolist.biz/work/addwork
     */
    public function addWork()
    {
        if (isset($_POST["create"]) && $_POST["create"] =='true') {
            $config = new \Todolist\Config('');
            $work_name      = isset($_POST["title"])  && !empty($_POST["title"])   ? $_POST["title"]   : '';
            $ending_date    = isset($_POST["ends"])   && !empty($_POST["ends"])    ? $_POST["ends"]    : '';
            $starting_date  = isset($_POST["starts"]) && !empty($_POST["starts"])  ? $_POST["starts"]  : '';
            $status         = isset($_POST["status"]) && !empty($_POST["status"])  ? $_POST["status"]  : '';
            $workModel      = $config->loadModel('Work');
            $response       = $workModel->addWork($work_name, $starting_date, $ending_date, $status);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
    
    /**
     * ACTION: updateWork
     * http://todolist.biz/work/updateWork
     */
    public function updateWork()
    {
        if (isset($_POST["update"]) && $_POST["update"] =='true') {
            $config = new \Todolist\Config('');
            $id             = isset($_POST["id"])     && !empty($_POST["id"])      ? $_POST["id"]      : '';
            $work_name      = isset($_POST["title"])  && !empty($_POST["title"])   ? $_POST["title"]   : '';
            $ending_date    = isset($_POST["ends"])   && !empty($_POST["ends"])    ? $_POST["ends"]    : '';
            $starting_date  = isset($_POST["starts"]) && !empty($_POST["starts"])  ? $_POST["starts"]  : '';
            $status         = isset($_POST["status"]) && !empty($_POST["status"])  ? $_POST["status"]  : '';
            $workModel      = $config->loadModel('Work');
            $response       = $workModel->updateWork($id, $work_name, $starting_date, $ending_date, $status);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }

    /**
     * ACTION: deleteWork
     * http://todolist.biz/work/deletework
     * @param int $work_id Id of the to-delete work
     */
    public function deleteWork()
    {
        if (isset($_POST["del"]) && $_POST["del"] =='true' && !empty($_POST["id"])) {
            $id      = isset($_POST["id"])  && !empty($_POST["id"])   ? $_POST["id"]   : '';
            $config     = new \Todolist\Config('');
            $workModel  = $config->loadModel('Work');
            $response   = $workModel->deleteWork($id);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    }
}
