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
	$code = $description = $salary = "";
	$code_err = $description_err = $salary_err = "";
	if(isset($_POST["add"])){
		if (empty($_POST["code"])){
			$code_err = "Code is required";
		}
		else {
			$code = modifyInputData($_POST["code"]);
		}
		
		if (empty($_POST["description"])){
			$description_err = "Description is required";
		}
		else {
			$description = modifyInputData($_POST["description"]);
		}
		
		if (empty($_POST["salary"])){
			$salary_err = "Salary Amount is required";
		}
		else {
			$salary = modifyInputData($_POST["salary"]);
		}
		
		$query = "Insert into job_details(Code, Description, Salary) values('".$code."', '".$description."', '".$salary."');";
		
		if($code_err or $description_err or $salary_err){
			echo '<script>alert("Please enter the details!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else if(mysqli_query($conn, $query)){
			echo '<script>alert("New Job Details added successfully!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else
			$db_status = "<span style="."font-family:Sans-serif;color:white;background-color:red;"."><b>Error: ".$db_query."<br>".mysqli_connect_error."</b></span>";
		
	}
	
	// Edit the existing record
	$original_code = $edit_code =  $edit_description = $edit_salary = "";
	$edit_code_err = $edit_description_err = $edit_salary_err = "";
	if(isset($_POST["update"])){
		if (empty($_POST["editcode"])){
			$edit_code_err = "Code is required";
		}
		else {
			$edit_code = modifyInputData($_POST["editcode"]);
		}
		
		$original_code = modifyInputData($_POST["originalcode"]);
		
		if (empty($_POST["editdescription"])){
			$edit_description_err = "Description is required";
		}
		else {
			$edit_description = modifyInputData($_POST["editdescription"]);
		}
		
		if (empty($_POST["editsalary"])){
			$edit_salary_err = "Salary Amount is required";
		}
		else {
			$edit_salary = modifyInputData($_POST["editsalary"]);
		}
		
		$query = "Update job_details set Code='".$edit_code."', Description='".$edit_description."', Salary='".$edit_salary."' where Code='".$original_code."'";
		
		if($edit_code_err or $edit_description_err or $edit_salary_err){
			echo '<script>alert("Please enter the details!!");</script>';
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
	$delete_code = "";
	if(isset($_POST["confirmdelete"])){
		$delete_code = modifyInputData($_POST["deletecode"]);
		
		$query = "Delete From Job_Details where Code='".$delete_code."'";
		
		if(mysqli_query($conn, $query)){
			echo '<script>alert("Record Deleted successfully!!");</script>';
			echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
		}
		else
			$db_status = "<span style="."font-family:Sans-serif;color:white;background-color:red;"."><b>Error: ".$db_query."<br>".mysqli_connect_error."</b></span>";	
	}
	
	if(isset($_POST["canceldelete"])){
		echo "<script>document.getElementById('delete').style.display = ".'"none"'.";</script>";
		echo '<meta http-equiv="refresh" content="0;URL=http://localhost:8012/Payroll_Application/index.php">';
	}
	
	// Initially displaying the table headers
	$table_data = "
		<table class="."table".">
			<tr>
				<th class="."tableHeader".">Code</th>
				<th class="."tableHeader".">Description</th>
				<th class="."tableHeader".">Salary (Wages/Hour)</th>
			</tr>
	";
	
	// Based on the data on DB, it will shows the column
	$query = "select Code, Description, Salary from Job_Details";
	$result = mysqli_query($conn, $query);
	
	if(mysqli_num_rows($result) == 0){
		$table_data = $table_data."</table>";
		echo $table_data;
		echo "<h4 style="."color:white;font-size:14px;".">No Records Found !!!</h4>";
		
	}
	else{
		while($row = mysqli_fetch_assoc($result)){
			$table_data = $table_data.
			"
				<tr>
					<td class="."tableData".">".$row["Code"]."</td>
					<td class="."tableData".">".$row["Description"]."</td>
					<td class="."tableData".">".$row["Salary"]."</td>
					<td class="."tableData"."><button class="."button"." onclick="."editRecord('".$row["Code"]."','".$row["Description"]."','".$row["Salary"]."')".">Edit</button></td>
					<td class="."tableData"."><button class="."button"." onclick="."deleteRecord('".$row["Code"]."','".$row["Description"]."','".$row["Salary"]."')".">Delete</button></td>
				</tr>
			";
		}
		$table_data = $table_data."</table>";
		echo $table_data;
	}
	
	// writing the function for removing special chars if any
	function modifyInputData($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	// Closing the connection
	mysqli_close($conn);
	
?>