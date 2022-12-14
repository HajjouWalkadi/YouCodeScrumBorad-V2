<?php
    //INCLUDE DATABASE FILE
    include('database.php');
    //SESSSION IS A WAY TO STORE DATA TO BE USED ACROSS MULTIPLE PAGES
    session_start();

    //ROUTING
    if(isset($_POST['saveTask'])){      
        $title = $_POST['taskTitle'];
        $type = $_POST['taskType'];
        $priority = $_POST['taskPriority'];
        $status = $_POST['taskStatus'];
        $date = $_POST['taskDate'];
        $description = $_POST['taskDescription'];
   
        saveTask($title, $type, $priority, $status, $date, $description);
    }
    
    
    if(isset($_POST['update'])){ 
        $id = $_POST['taskId'];
        $title = $_POST['taskTitle'];
        $type = $_POST['taskType'];
        $priority = $_POST['taskPriority'];
        $status = $_POST['taskStatus'];
        $date = $_POST['taskDate'];
        $description = $_POST['taskDescription'];
   
        updateTask($id, $title, $type, $priority, $status, $date, $description);
    }

    if(isset($_POST['delete'])){ 
        $id = $_POST['taskId'];
        
        deleteTask($id);
    }

    function counter($countStatus){
        global $conn;
        $sql = "SELECT * FROM tasks where status_id = $countStatus";
        $result = mysqli_query($conn,$sql);
        $res = mysqli_fetch_all($result,MYSQLI_ASSOC);
        echo count($res);
    }

    //____________fonction afficher:_______________
    function getTasks($status,$icon)
    {
        //CODE HERE
        //SQL SELECT
        global $conn;
        // echo "Fetch all tasks";
        $sql = "SELECT tasks.id as id , title , types.name as type , priorities.name as priority, status_id ,priority_id , task_datetime , description FROM tasks 
        INNER JOIN types on types.id_types = tasks.type_id 
        INNER JOIN priorities on tasks.priority_id = priorities.id_priorities 
        INNER JOIN statuses on tasks.status_id=statuses.id_statuses WHERE status_id = $status";



        $result = mysqli_query($conn,$sql);

        while ($row = mysqli_fetch_assoc( $result) ) {
            $icon = '';
            
            $status = $row["status_id"];
                if($status == 1){
                    $icon='fa fa-circle-question';
                }
                
                else if ($status == 2){
                    $icon='fa fa-spinner';
              
                }
                else if ($status == 3){
                    $icon='fa fa-circle-check';
                    
                }

            echo ' 

            <button onclick=editTask(this,'.$row["id"].',"'.$row["type"].'","'.$row['priority_id'].'","'.$row['status_id'].'","'.$row['task_datetime'].'")  class="d-flex button border  w-100 p-1">
            <div class="col-md-1">
                <i class="'.$icon.' text-success"></i> 
            </div>
            <div class="text-start col-md-11 ">
                <div class="fw-bolder">'.$row['title'].'</div>
                <div class="">
                    <div class="text-gray">#'.$row['id'].' created in '.$row['task_datetime'].'</div>
                    <div class="" title="">'.$row['description'].'</div>
                </div>
                <div class="">
                    <span class="col-md-auto btn btn-primary text-white ">'.$row['priority'].'</span>
                    <span class="col-md-auto btn btn-gray text-dark">'.$row['type'].'</span>
                </div>
            </div>
            </button>';
        }

    }

    function saveTask($title, $type, $priority, $status, $date, $description)
    {
        
        //CODE HERE
        //SQL INSERT
        global $conn;
        $sql = "INSERT INTO tasks(title, type_id, priority_id, status_id, task_datetime, description) 
        VALUES('$title', '$type', '$priority', '$status', '$date', '$description');";
        $result = mysqli_query($conn,$sql);

        $_SESSION['message'] = "Task has been added successfully !";
        header('location: index.php');
		
    }
    //function update:
    function updateTask($id, $title, $type, $priority, $status, $date, $description)
    {
        //CODE HERE
        //SQL UPDATE
        global $conn;
        $sql = " UPDATE tasks SET `title`='$title',`type_id`='$type',`priority_id`='$priority',`status_id`='$status',`task_datetime`='$date',`description`='$description' 
        WHERE id = $id ";
        mysqli_query($conn,$sql);
        $_SESSION['message'] = "Task has been updated successfully !";
		header('location: index.php');
    }
    // function delete:
    function deleteTask($id)
    {
           //CODE HERE
        //SQL DELETE
        
        global $conn;
        $sql = "DELETE FROM tasks where id = $id";
        mysqli_query($conn,$sql);
        $_SESSION['message'] = "Task has been deleted successfully !";
		header('location: index.php');
    }

?>