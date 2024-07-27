<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "payroll_application";
	$db_status = "";
	
	//Connect with DB
	$conn = mysqli_connect($servername, $username, $password, $dbname, 3306);
	
	//Check connection
	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
	// Adding the new records
	$emp_code = $emp_name = $job_title = $duration = "";
	$emp_code_err = $emp_name_err = $job_title_err = $duration_err = "";
	if(isset($_POST["addemp"])){
		if (empty($_POST["empcode"])){
			$emp_code_err = "Employee Code is required";
		}
		else {
			$emp_code = modifyInputData($_POST["empcode"]);
		}
		
		if (empty($_POST["empname"])){
			$emp_name_err = "Employee Name is required";
		}
		else {
			$emp_name = modifyInputData($_POST["empname"]);
		}
		
		if (empty($_POST["jobtitle"])){
			$job_title_err = "Job Title is required";
		}
		else {
			$job_title = modifyInputData($_POST["jobtitle"]);
		}
		
		if (empty($_POST["duration"])){
			$duration_err = "Duration is required";
		}
		else {
			$duration = modifyInputData($_POST["duration"]);
		}
		
		$query = "Insert into employee_details(EMP_Code, EMP_Name, Job_Title, Duration) values('".$emp_code."', '".$emp_name."', '".$job_title."', '".$duration."');";
		
		if($emp_code_err or $emp_name_err or $job_title_err or $duration_err){
			echo '<script>alert("Please enter the Employee details to add!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else if(mysqli_query($conn, $query)){
			echo '<script>alert("New Employee Added!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else
			$db_status = "<span style="."font-family:Sans-serif;color:white;background-color:red;"."><b>Error: ".$db_query."<br>".mysqli_connect_error."</b></span>";
		
	}
	
	// Edit the existing record
	$original_emp_code = $edit_emp_code = $edit_emp_name = $edit_job_title = $edit_duration = "";
	$edit_emp_code_err = $edit_emp_name_err = $edit_job_title_err = $edit_duration_err = "";
	if(isset($_POST["updateemp"])){
		if (empty($_POST["editempcode"])){
			$edit_emp_code_err = "Employee Code is required";
		}
		else {
			$edit_emp_code = modifyInputData($_POST["editempcode"]);
		}
		
		if (empty($_POST["editempname"])){
			$edit_emp_name_err = "Employee Name is required";
		}
		else {
			$edit_emp_name = modifyInputData($_POST["editempname"]);
		}
		
		if (empty($_POST["editjobtitle"])){
			$edit_job_title_err = "Job Title is required";
		}
		else {
			$edit_job_title = modifyInputData($_POST["editjobtitle"]);
		}
		
		if (empty($_POST["editduration"])){
			$edit_duration_err = "Duration is required";
		}
		else {
			$edit_duration = modifyInputData($_POST["editduration"]);
		}
		
		$original_emp_code = modifyInputData($_POST["originalempcode"]);
		
		$query = "Update employee_details set EMP_Code='".$edit_emp_code."', EMP_Name='".$edit_emp_name."', Job_Title='".$edit_job_title."', Duration='".$edit_duration."' where EMP_Code='".$original_emp_code."';";
		
		if($edit_emp_code_err or $edit_emp_name_err or $edit_job_title_err or $edit_duration_err){
			echo '<script>alert("Please enter the Employee details to add!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else if(mysqli_query($conn, $query)){
			echo '<script>alert("Record Updated successfully!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else
			$db_status = "<span style="."font-family:Sans-serif;color:white;background-color:red;"."><b>Error: ".$db_query."<br>".mysqli_connect_error."</b></span>";	
	}
	
	// Delete the record based on user's needs
	$delete_emp_code = "";
	if(isset($_POST["confirmempdelete"])){
		$delete_emp_code = modifyInputData($_POST["deleteempcode"]);
		
		$query = "Delete From employee_details where EMP_Code='".$delete_emp_code."'";
		
		if(mysqli_query($conn, $query)){
			echo '<script>alert("Record Deleted successfully!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else
			$db_status = "<span style="."font-family:Sans-serif;color:white;background-color:red;"."><b>Error: ".$db_query."<br>".mysqli_connect_error."</b></span>";	
	}
	
	if(isset($_POST["cancelempdelete"])){
		echo "<script>document.getElementById('deleteemp').style.display = ".'"none"'.";</script>";
		echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
	}
	
	// Initially displaying the table headers
	$table_data = "
		<table class="."table".">
			<tr>
				<th class="."tableHeader".">Employee Code</th>
				<th class="."tableHeader".">Employee Name</th>
				<th class="."tableHeader".">Job Title</th>
				<th class="."tableHeader".">Duration(In Years)</th>
			</tr>
	";
	
	// If user searches for something, the below code works
	$search = $search_err = "";
	if(isset($_POST["searchbutton"])){
		if (empty($_POST["searchbox"])){
			echo "<script>alert('Search Value is required!!');</script>";
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
			$search_err = "1";
		}
		else {
			$search = modifyInputData($_POST["searchbox"]);
		}
		
		$query = "select EMP_Code, EMP_Name, Job_Title, Duration from employee_details where EMP_Code = '".$search."';";
		$result = mysqli_query($conn, $query);
		if(mysqli_num_rows($result) == 0 && $search_err != "1"){
			$table_data = $table_data."</table>";
			//echo $table_data;
			//echo "<h4 style="."color:white;font-size:18px;".">No Records Found !!!</h4>";
			echo "<script>alert('No Records Found through search!!. Search with different value.');</script>";
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else{
			echo "<script>alert('".mysqli_num_rows($result)." Record(s) Found through search!!');</script>";
			while($row = mysqli_fetch_assoc($result)){
				$table_data = $table_data.
				"
					<tr>
						<td class="."tableData".">".$row["EMP_Code"]."</td>
						<td class="."tableData".">".$row["EMP_Name"]."</td>
						<td class="."tableData".">".$row["Job_Title"]."</td>
						<td class="."tableData".">".$row["Duration"]."</td>
						<td class="."tableData"."><button class="."button"." onclick="."editEmpRecord('".$row["EMP_Code"]."','".$row["EMP_Name"]."','".$row["Job_Title"]."','".$row["Duration"]."')".">Edit</button></td>
						<td class="."tableData"."><button class="."button"." onclick="."deleteEmpRecord('".$row["EMP_Code"]."','".$row["EMP_Name"]."','".$row["Job_Title"]."','".$row["Duration"]."')".">Delete</button></td>
					</tr>
					<tr><td><a href=".'"http://localhost:8012/Payroll_Application/index.php"'.">".'<-'."Back to results</a></td></tr>
				";
		}
		$table_data = $table_data."</table>";
		echo $table_data;
		}
	}
	else{
		// Based on the data on DB, it will shows the column
		$query = "select EMP_Code, EMP_Name, Job_Title, Duration from employee_details;";
		$result = mysqli_query($conn, $query);
		
		if(mysqli_num_rows($result) == 0){
			$table_data = $table_data."</table>";
			echo $table_data;
			echo "<h4 style="."color:white;font-size:18px;".">No Records Found !!!</h4>";
			
		}
		else{
			while($row = mysqli_fetch_assoc($result)){
				$table_data = $table_data.
				"
					<tr>
						<td class="."tableData".">".$row["EMP_Code"]."</td>
						<td class="."tableData".">".$row["EMP_Name"]."</td>
						<td class="."tableData".">".$row["Job_Title"]."</td>
						<td class="."tableData".">".$row["Duration"]."</td>
						<td class="."tableData"."><button class="."button"." onclick="."editEmpRecord('".$row["EMP_Code"]."','".$row["EMP_Name"]."','".$row["Job_Title"]."','".$row["Duration"]."')".">Edit</button></td>
						<td class="."tableData"."><button class="."button"." onclick="."deleteEmpRecord('".$row["EMP_Code"]."','".$row["EMP_Name"]."','".$row["Job_Title"]."','".$row["Duration"]."')".">Delete</button></td>
					</tr>
				";
			}
			$table_data = $table_data."</table>";
			echo $table_data;
		}
	}
	
	$dropdown_options="";
	$query = "Select Description from job_details;";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_assoc($result)){
		$dropdown_options = $dropdown_options.
		'
			<option value="'.$row["Description"].'">'.$row["Description"].'</option>
		';
	}
		
	// Closing the connection
	mysqli_close($conn);
	
?>