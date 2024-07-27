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
	
	// Output variable is used to display after all the calculations are done.
	$output = "";
	$report_buttons = "";
	
	// Fetching values for Employee
	$employee_dropdown_options="";
	$query = "Select EMP_Name from employee_details;";
	$result = mysqli_query($conn, $query);
	while($row = mysqli_fetch_assoc($result)){
		$employee_dropdown_options = $employee_dropdown_options.
		'
			<option value="'.$row["EMP_Name"].'">'.$row["EMP_Name"].'</option>
		';
	}
	
	// Fetching values for Years
	$year_dropdown_options = "";
	$start_year = 2000; $end_year = date('Y');
	while($end_year!=$start_year-1){
		$year_dropdown_options = $year_dropdown_options.'<option value="'.$end_year.'">'.$end_year.'</option>';
		$end_year--;
	}
	
	// After clicking Calculate button, the details are shown along with Total Salary.
	$employee_select = $month_select = $employee_code = $designation = $years_of_experience = $salary_per_hour = $original_salary_per_hour = $year_select = $hours_select = "";
	$total_salary = $basic_salary = $housing = $transport = $salary_in_hand = $tax = $hours_select_err = "";
	if(isset($_POST["showcalculation"])){
		$employee_select = $_POST["employeeselect"];
		$month_select = $_POST["monthselect"];
		$year_select = $_POST["yearselect"];
		if(empty($_POST["hoursselect"]))
			$hours_select_err = "Total Hours is required!!";
		else
			$hours_select = modifyInputData($_POST["hoursselect"]);
		
		if($hours_select_err){
			echo "<script>alert('Total Hours is required!!');</script>";
			echo '<meta http-equiv="refresh" content="0; URL=http://localhost:8012/Payroll_Application/">';
		}
		else{
			// Selecting required data from DB by joining together.
			$query = "SELECT e.EMP_Code, e.Job_Title, e.Duration, j.Salary 
					FROM job_details j INNER JOIN employee_details e on j.Description = e.Job_Title 
					WHERE e.EMP_Name = '".$employee_select."';";
			
			$result = mysqli_query($conn, $query);
			$row = mysqli_fetch_assoc($result);
			
			$employee_code = $row["EMP_Code"];
			$designation = $row["Job_Title"];
			$years_of_experience = $row["Duration"];
			$salary_per_hour = $row["Salary"];
			$original_salary_per_hour = $salary_per_hour;
			
			// MAKING CALCULATION
			// 1. Define Wages/hour as it depends on no.of years of experience
			if(intval($years_of_experience)>1){
				$temp_year = intval($years_of_experience);
				while(intval($temp_year)!=0){
					$salary_per_hour = $salary_per_hour+($salary_per_hour*(10/100));
					$temp_year = $temp_year-1;
				}
			}
			
			// Calculating total Salary
			$total_salary = $salary_per_hour*$hours_select;
			
			// Separating the Base Salary, House and Transport
			$basic_salary = (64/100)*$total_salary;
			$housing = (24/100)*$total_salary;
			$transport = (12/100)*$total_salary;
			
			// Calculating the tax
			// Tax will only applicable if Basic Salary is greater than 1000. If so, 30% of taxable amount will be deducted. 
			// Taxable Amount is nothing but subtracting 1000 from Basic Salary.
			$tax=0;
			if($basic_salary>1000){
				$taxable_amount = $basic_salary - 1000;
				$tax = (30/100)*$taxable_amount;
			}
			
			$salary_in_hand = ($basic_salary-$tax)+$housing+$transport;
			echo '<script>alert("Payroll Calculated!!");</script>';
			$output=$output.'
			<hr>
			<h1 style="text-align:center;">Payroll Calculation '.$month_select.' '.$year_select.'</h1>
			<table class="table">
				<tr>
					<td style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Name of the Employee</b></td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>'.$employee_select.'</b></td>
				</tr>
				<tr>
					<td style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Employee Code</b></td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.$employee_code.'</td>
				</tr>
				<tr>
					<td style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Designation</b></td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.$designation.'</td>
				</tr>
				<tr>
					<td style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Years of Experience in current company</b></td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.$years_of_experience.'</td>
				</tr>
				<tr>
					<td style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Salary(Wages/Hour)</b></td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.$original_salary_per_hour.'</td>
				</tr>
				<tr>
					<td style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Total Hours worked current month</b></td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.$hours_select.'</td>
				</tr>
				<tr>
					<td style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Salary in Hand</b></td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.number_format($salary_in_hand,2).'</td>
				</tr>
			</table>
			<h1 style="text-align:center;">Below are the details of the Payroll</h1>
			<div style="text-align:center;"><table class="table">
				<tr>
					<th style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Total Salary</b></th>
					<th style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Basic Salary</b></th>
					<th style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Housing</b></th>
					<th style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Transport</b></th>
					<th style="font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;"><b>Tax</b></th>
				</tr>
				<tr>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.number_format($total_salary,2).'</td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.number_format($basic_salary,2).'</td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.number_format($housing,2).'</td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.number_format($transport,2).'</td>
					<td style="text-align:center;font-size:18px;font-family:Sans-serif;margin:1px;font-size:18px;padding:10px;border:1px solid;max-width:33.33%;">'.number_format($tax,2).'</td>
				</tr>
			</table>
			<span><b style="color:white;">*Tax will only applicable if Basic Salary is greater than 1000. If so, 30% of taxable amount will be deducted from Basic Salary.</b></span>
			</div>
			<hr>';
			$report_buttons = $report_buttons.'
				<button id="generatehtmlreport" class="button" onclick="generateHTMLReport('."'".$employee_select.'_'.$month_select.'_'.$year_select."'".','."'payrollcalculation'".','."'".$employee_select.' Report'."'".')">Generate HTML Report</button>
				<button id="printpdfreport" class="button" onclick="printPDFReport('."'payrollcalculation'".','."'".$employee_select.' Report'."'".')">Print PDF Report</button>
			';
		}
	}
	
	// Closing the connection
	mysqli_close($conn);
?>