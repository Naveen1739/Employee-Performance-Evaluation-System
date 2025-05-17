<?php include 'db_connect.php'; // Ensure database connection ?>

<div class="col-lg-12">
    <div class="card card-outline card-danger">
        <div class="card-header">
            <h3 class="card-title"><b>Layoff Employee List</b></h3>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="layoff_list">
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
                        <th>Evaluator</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    
                    // Fetching department, designation, and evaluator details
                    $designations = $conn->query("SELECT * FROM designation_list");
                    $design_arr = ["Unset"];
                    while ($row = $designations->fetch_assoc()) {
                        $design_arr[$row['id']] = $row['designation'];
                    }

                    $departments = $conn->query("SELECT * FROM department_list");
                    $dept_arr = ["Unset"];
                    while ($row = $departments->fetch_assoc()) {
                        $dept_arr[$row['id']] = $row['department'];
                    }

                    $evaluators = $conn->query("SELECT * FROM evaluator_list");
                    $eval_arr = ["Not Assigned"];
                    while ($row = $evaluators->fetch_assoc()) {
                        $eval_arr[$row['id']] = $row['firstname'] . " " . $row['lastname'];
                    }

                    // Fetch Layoff Employees
                    $qry = $conn->query("SELECT * FROM layoff_employee_list ORDER BY lastname ASC");
                    if (!$qry) {
                        die("Query failed: " . $conn->error);
                    }

                    while ($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><b><?php echo ucwords($row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']) ?></b></td>
                        <td><b><?php echo $row['email'] ?></b></td>
                        <td><b><?php echo $row['gender'] ?></b></td>
                        <td><b><?php echo $row['contact'] ?></b></td>
                        <td><b><?php echo $row['address'] ?></b></td>
                        <td><b><?php echo isset($dept_arr[$row['department_id']]) ? $dept_arr[$row['department_id']] : 'Unknown Department' ?></b></td>
                        <td><b><?php echo isset($design_arr[$row['designation_id']]) ? $design_arr[$row['designation_id']] : 'Unknown Designation' ?></b></td>
                        <td><b><?php echo ($row['evaluator_id'] && isset($eval_arr[$row['evaluator_id']])) ? $eval_arr[$row['evaluator_id']] : 'Not Assigned' ?></b></td>
                    </tr>   
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#layoff_list').dataTable();
    });
</script>
