<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_employee">
                    <i class="fa fa-plus"></i> Add New Employee
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table tabe-hover table-bordered" id="list">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Evaluator</th> <!-- Updated -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $designations = $conn->query("SELECT * FROM designation_list ");
                    $design_arr[0] = "Unset";
                    while ($row = $designations->fetch_assoc()) {
                        $design_arr[$row['id']] = $row['designation'];
                    }

                    $departments = $conn->query("SELECT * FROM department_list ");
                    $dept_arr[0] = "Unset";
                    while ($row = $departments->fetch_assoc()) {
                        $dept_arr[$row['id']] = $row['department'];
                    }

                    $evaluators = $conn->query("SELECT * FROM evaluator_list ");
                    $eval_arr[0] = "Not Assigned";
                    while ($row = $evaluators->fetch_assoc()) {
                        $eval_arr[$row['id']] = $row['firstname'] . " " . $row['lastname'];
                    }

                    // Updated Query to Ensure Evaluator is Properly Fetched
                    $qry = $conn->query("SELECT e.*, 
                        CONCAT(e.firstname, ' ', e.middlename, ' ', e.lastname) AS name, 
                        ev.firstname AS evaluator_first, ev.lastname AS evaluator_last
                        FROM employee_list e
                        LEFT JOIN evaluator_list ev ON e.evaluator_id = ev.id
                        ORDER BY e.lastname ASC");

                    while ($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><b><?php echo ucwords($row['name']) ?></b></td>
                        <td><b><?php echo $row['email'] ?></b></td>
                        <td><b><?php echo $row['gender'] ?></b></td>
                        <td><b><?php echo $row['contact'] ?></b></td>
                        <td><b><?php echo $row['address'] ?></b></td>
                        <td><b><?php echo isset($dept_arr[$row['department_id']]) ? $dept_arr[$row['department_id']] : 'Unknown Department' ?></b></td>
                        <td><b><?php echo isset($design_arr[$row['designation_id']]) ? $design_arr[$row['designation_id']] : 'Unknown Designation' ?></b></td>
                        <td><b><?php echo ($row['evaluator_id'] && isset($eval_arr[$row['evaluator_id']])) ? $eval_arr[$row['evaluator_id']] : 'Not Assigned' ?></b></td> <!-- Updated -->
                        <td class="text-center">
                            <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                              Action
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item view_employee" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item" href="./index.php?page=edit_employee&id=<?php echo $row['id'] ?>">Edit</a>
                              <div class="dropdown-divider"></div>
                              <a class="dropdown-item delete_employee" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
                            </div>
                        </td>
                    </tr>   
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
  $(document).ready(function(){
    $('#list').dataTable();

    $(document).on('click', '.delete_employee', function(){
        let emp_id = $(this).attr('data-id');

        if (!emp_id) {
            alert("Error: Employee ID not found.");
            return;
        }

        _conf("Are you sure to delete this Employee?", "delete_employee", [emp_id]);
    });
});

function delete_employee(emp_id){
    start_load();

    $.ajax({
        url: 'ajax.php?action=layoff_employee',  // Ensure action is correct
        method: 'POST',
        data: { id: emp_id },
        success: function(resp) {
            console.log("Server Response: ", resp); // Debugging output
            
            if (resp.trim() == "1") {
                alert_toast("Employee moved to Layoff List", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert_toast("Failed to move employee", 'error');
            }
        },
        error: function(xhr, status, error) {
            console.log("AJAX Error: ", error);
        }
    });
}

</script>
