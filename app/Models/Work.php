<?php
namespace Todolist\Models; 

class Work
{
    
    /**
     * @param object $db A PDO database connection
     */
    public function __construct($db)
    {
        try {
            $this->db = $db;
        } catch (PDOException $e) {
            exit('Database connection failed.');
        }
    }
    
    /**
     * Get all work from database
     */
    public function getAllWork()
    {
        $sql = "SELECT * FROM work";
        $query = $this->db->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
    
    /**
     * Add a work to database
     * @param string $work_name
     * @param string $starting_date
     * @param string $ending_date
     * @param int    $status
     */
    public function addWork($work_name, $starting_date, $ending_date, $status)
    {
        $response = array(
            'status'    => '400',
            'message'   =>  'error',
            'result'    =>  ''
        );
        if($work_name==''){
            $response['message'] = "Title is required";
            goto RESPONSE;
        }
        if($starting_date==''){
            $response['message'] = "Starting date is required";
            goto RESPONSE;
        }
        if($ending_date==''){
            $response['message'] = "Ending date is required";
            goto RESPONSE;
        }
        if($status==''){
            $response['message'] = "Status is required";
            goto RESPONSE;
        }
        // clean the input from javascript code for example
        $work_name      = strip_tags($work_name);
        $ending_date    = strip_tags($ending_date);
        $status         = strip_tags($status);
        $data = array(
            ':work_name'        => $work_name,
            ':starting_date'    => $starting_date,
            ':ending_date'      => $ending_date,
            ':status'           => $status
        );
        $sql = "INSERT INTO work (work_name, starting_date, ending_date, status) 
                          VALUES (:work_name, :starting_date, :ending_date, :status)";
        $sql = $this->db->prepare($sql);
        $result = $sql->execute($data);
        if($result){
            $response = array(
                'status'    => '200',
                'message'   =>  'success',
                'result'    =>  $this->db->lastInsertId()
            );
        }else{
            $response['result'] = $sql->errorCode();
        }
        RESPONSE:
            return $response;
    }
    
    /**
     * Add a work to database
     * @param string $work_name
     * @param string $starting_date
     * @param string $ending_date
     * @param int    $status
     */
    public function updateWork($id, $work_name, $starting_date, $ending_date, $status)
    {
        $response = array(
            'status'    => '400',
            'message'   =>  'error',
            'result'    =>  ''
        );
        if($id==''){
            $response['message'] = "Id does not exist";
            goto RESPONSE;
        }
        if($work_name==''){
            $response['message'] = "Title is required";
            goto RESPONSE;
        }
        if($starting_date==''){
            $response['message'] = "Starting date is required";
            goto RESPONSE;
        }
        if($ending_date==''){
            $response['message'] = "Ending date is required";
            goto RESPONSE;
        }
        if($status==''){
            $response['message'] = "Status is required";
            goto RESPONSE;
        }
        // clean the input from javascript code for example
        $work_name      = strip_tags($work_name);
        $ending_date    = strip_tags($ending_date);
        $status         = strip_tags($status);
        $data = array(
            ':id'               => $id,
            ':work_name'        => $work_name,
            ':starting_date'    => $starting_date,
            ':ending_date'      => $ending_date,
            ':status'           => $status
        );
        $sql = "UPDATE work SET work_name = :work_name, starting_date = :starting_date, ending_date = :ending_date, status = :status WHERE id = :id";
        $sql = $this->db->prepare($sql);
        $result = $sql->execute($data);
        if($result){
            $response = array(
                'status'    => '200',
                'message'   =>  'success',
            );
        }else{
            $response['result'] = $sql->errorCode();
        }
        RESPONSE:
            return $response;
    }

    /**
     * Delete a work in the database
     * @param int $word_id Id of word
     */
    public function deleteWork($word_id)
    {
        $response = array(
            'status'    => '200',
            'message'   =>  'success',
            'result'    =>  ''
        );
        if($word_id==''){
            $response = array(
                'status'    => '400',
                'message'   =>  'error',
            );
            goto RESPONSE;
        }
        $sql = "DELETE FROM work WHERE id = :word_id";
        $query = $this->db->prepare($sql);
        $query->execute(array(':word_id' => $word_id));
        RESPONSE:
            return $response;
    }
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}
