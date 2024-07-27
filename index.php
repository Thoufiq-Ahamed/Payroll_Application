<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payroll Application</title>
	<link rel="icon" href="favicon.ico">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script>
		function printPDFReport(idname, title){
			$('#printpdfreport').live("click", function(){
				var printWindow = window.open();
				var content = $('#'+idname).html();
				printWindow.document.write('<html><head><title>'+title+'</title></head><body>');
				printWindow.document.write(content);
				printWindow.document.write('</body></html>');
				printWindow.document.close();
				printWindow.print();
			});
		}
		function generateHTMLReport(filename, idname, title){
				var ref = URL.createObjectURL(new Blob([document.getElementById(idname).innerHTML],{type:'text/html'}));
				var link = document.createElement("a");
				link.href=ref;
				link.download=filename+'.html';
				link.click();
				link.remove();
				URL.revokeObjectURL(ref);
				//document.getElementById(idname).innerHTML = '<?php echo '<meta http-equiv="refresh" content="0; URL=http://localhost:8012/Payroll_Application/index.php">';?>';
		}
		function colorChange(idName){
			var i,x;
			x = document.getElementsByClassName("column");
			for(i=0;i<x.length;i++){
				x[i].style.color = "black";
			}
			document.getElementById(idName).style.color="white";
		}
		function openTab(Tabname){
			var i,x;
			x = document.getElementsByClassName("containerTab");
			for(i=0;i<x.length;i++){
				x[i].style.display="none";
			}
			document.getElementById(Tabname).style.display="block";
		}
		function show(idname){
			document.getElementById(idname).style.display="block";
		}
		function editRecord(code,desc,salary){
			document.getElementById('edit').style.display = "block";
			document.getElementById('editheading').innerHTML=desc;
			document.getElementById('editcode').value=code;
			document.getElementById('editdescription').value=desc;
			document.getElementById('editsalary').value=salary;
			document.getElementById('originalcode').value=code;
		}
		function deleteRecord(code,desc,salary){
			document.getElementById('delete').style.display = "block";
			document.getElementById('deletecode').value=code;
			document.getElementById('deletedescription').value=desc;
			document.getElementById('deletesalary').value=salary;
		}
		function editEmpRecord(empcode,empname,jobtitle, duration){
			document.getElementById('editemp').style.display = "block";
			document.getElementById('editempheading').innerHTML=empname;
			document.getElementById('editempcode').value=empcode;
			document.getElementById('editempname').value=empname;
			document.getElementById('editjobtitle').value=jobtitle;
			document.getElementById('editduration').value=duration;
			document.getElementById('originalempcode').value=empcode;
		}
		function deleteEmpRecord(empcode,empname,jobtitle, duration){
			document.getElementById('deleteemp').style.display = "block";
			document.getElementById('deleteempcode').value=empcode;
			document.getElementById('deleteempname').value=empname;
			document.getElementById('deletejobtitle').value=jobtitle;
			document.getElementById('deleteduration').value=duration;
		}
	</script>
	<style>
		body{
			background-color:grey;
		}

		.content{
			margin:auto;
			max-width:1200px;
			background-color:white;
			padding:10px;
		}
		
		.column{
			float:left;
			padding:10px;
			width:31.30%;
			cursor:pointer;
			text-align:center;
			font-size:18px;
			background-color:grey;
			margin:0.8px;
			border: 1px solid;
		}
		
		.containerTab{
			padding:10px;
			margin:1px;
			background-color:grey;
			width:98%;
		}
		
		.row:after{
			content:"";
			clear:both;
			display:table;
		}
		
		.table{
			margin:1px;
			border:1px solid;
			table-layout:auto;
			width:99%;
		}
		
		.tableHeader, .tableData{
			margin:1px;
			text-align:center;
			font-size:18px;
			padding:10px;
			border:1px solid;
			max-width:33.33%;
		}
		
		.button:hover{
			background-color:black;
			color:white;
		}
		
		.cancelbutton:hover{
			background-color:red;
			color:white;
		}
	</style>
</head>

<body>
	<div class="content">

		<div>
			<h1>Payroll Application</h1>
		</div>

		<div>
			<p>
				This is a Simple Payroll Application that most of the companies used for their Employees's financial
				activities including Salary.
				This application consists of three parts which have their own functionality.
				The Three Parts are:
			</p>
		</div>

		<div>
			<ul>
				<li>Job Title Library</li>
			</ul>
			<ul>
				<li>Employee Details</li>
			</ul>
			<ul>
				<li>Calculating Payroll</li>
			</ul>
		</div>

		<div class="row">
			<div id="Job_Title_Column" class="column" style="color:white;" onclick="colorChange('Job_Title_Column');openTab('b1');">Job Title</div>
			<div id="Employee_Detail_Column" class="column" onclick="colorChange('Employee_Detail_Column');openTab('b2');">Employee Detail</div>
			<div id="Payroll Calculation_Column" class="column" onclick="colorChange('Payroll Calculation_Column');openTab('b3');">Payroll Calculation</div>
		</div>
		
		<!-- The below code is for implementing the logic for Job Details. By Default, it is the first tab to be displayed when open the site. -->
		<div id="b1" class="containerTab" style="display:block;margin:1px;border:1px solid;">
			<div style="overflow-x:auto;">
				<?php 
					require 'Job_Details.php';
				?>
			</div>
			
			<div style="padding:5px">
				<button onclick="show('add_new')" class="button">Add New</button>
				<?php echo $db_status; ?>
			</div>
			
			<!-- The below code is for adding the new record for Job Details -->
			<div id="add_new" style="padding:5px;display:none;">
				<hr>
				<h1 style="color:white;font-family:Sans-serif;">Add a New Record</h1>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table>
						<tr>
							<td><label for="code" style="font-size:18px;font-family:Sans-serif;"><b>*Code:</b></label></td>
							<td><input type="text" id="code" name="code" placeholder="Enter the Job Code(Ex: MGR for Manager)" style="height:20px;width:300px;"></td>
							<td style="color:red;"><?php echo $code_err; ?></td>
						</tr>
						<tr>
							<td><label for="description" style="font-size:18px;font-family:Sans-serif;"><b>*Description:</b></label></td>
							<td><input type="text" id="description" name="description" placeholder="Enter the Description(Ex: Manager)" style="height:20px;width:300px;"></td>
							<td>*Use Underscores(_) if you are using spaces</td>
							<td style="color:red;"><?php echo $description_err; ?></td>
						</tr>
						<tr>
							<td><label for="salary" style="font-size:18px;font-family:Sans-serif;"><b>*Salary(Wages/Hour):</b></label></td>
							<td><input type="text" id="salary" name="salary" placeholder="Enter the Salary(Ex: 20)" style="height:20px;width:300px;"></td>
							<td style="color:red;"><?php echo $salary_err; ?></td>
						</tr>
						<tr>
							<td><input type="submit" id="addnew" name="add" value="Add" class="button"></td>
							<td><b>* - Required</b></td>
						</tr>
					</table>
				</form>
			</div>
			
			<!-- The below code is for editing the existing record for Job Details -->
			<div id="edit" style="padding:5px;display:none;">
				<hr>
				<h1 style="color:white;font-family:Sans-serif;">Edit the Record for <span id="editheading"></span></h1>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table>
						<tr>
							<td><label for="editcode" style="font-size:18px;font-family:Sans-serif;"><b>*Code:</b></label></td>
							<td><input type="text" id="editcode" name="editcode" style="height:20px;width:300px;"></td>
							<td style="color:red;"><?php echo $edit_code_err; ?></td>
						</tr>
						<tr>
							<td><label for="editdescription" style="font-size:18px;font-family:Sans-serif;"><b>*Description:</b></label></td>
							<td><input type="text" id="editdescription" name="editdescription" style="height:20px;width:300px;"></td>
							<td>*Use Underscores(_) if you are using spaces</td>
							<td style="color:red;"><?php echo $edit_description_err; ?></td>
						</tr>
						<tr>
							<td><label for="editsalary" style="font-size:18px;font-family:Sans-serif;"><b>*Salary(Wages/Hour):</b></label></td>
							<td><input type="text" id="editsalary" name="editsalary" style="height:20px;width:300px;"></td>
							<td><input type="text" id="originalcode" name="originalcode" style="display:none;"></td>
							<td style="color:red;"><?php echo $edit_salary_err; ?></td>
						</tr>
						<tr>
							<td><input type="submit" id="update" name="update" value="Update" class="button"></td>
							<td><b>* - Required</b></td>
						</tr>
					</table>
				</form>
			</div>
			
			<!-- The below code is for delete the record when click on Delete button in Job_Details section -->
			<div id="delete" style="padding:5px;display:none;">
				<hr>
				<h1 style="color:red;font-family:Sans-serif;">Are you sure to delete the below displaying record?</h1>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table>
						<tr>
							<td><label for="deletecode" style="font-size:18px;font-family:Sans-serif;"><b>Code:</b></label></td>
							<td>
								<input type="text" id="deletecode" name="deletecode" style="font-family:Sans-serif;font-size:18px;height:20px;width:300px;background-color:grey;color:black;border:none;" readonly>
							</td>
						</tr>
						<tr>
							<td><label for="deletedescription" style="font-size:18px;font-family:Sans-serif;"><b>Description:</b></label></td>
							<td>
								<input type="text" id="deletedescription" name="deletedescription" style="font-family:Sans-serif;font-size:18px;height:20px;width:300px;background-color:grey;color:black;border:none;" readonly>
							</td>
						</tr>
						<tr>
							<td><label for="deletesalary" style="font-size:18px;font-family:Sans-serif;"><b>Salary(Wages/Hour):</b></label></td>
							<td>
								<input type="text" id="deletesalary" name="deletesalary" style="font-family:Sans-serif;font-size:18px;height:20px;width:300px;background-color:grey;color:black;border:none;" readonly>
							</td>
						</tr>
					</table><br>
					<input type="submit" id="confirmdelete" name="confirmdelete" value="YES" class="cancelbutton">
					<input type="submit" id="canceldelete" name="canceldelete" value="NO" class="button">
				</form>
			</div>
		</div>

		<!-- The below code is for implementing the logic for Employee Details -->
		<div id="b2" class="containerTab" style="display:none;margin:1px;border:1px solid;">
			<div style="overflow-x:auto;">
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table>
						<tr>
							<td><input type="text" id="searchbox" name="searchbox" placeholder="Search for Employee by Employee Code.." style="height:20px;width:300px;"></td>
							<td><input type="submit" id="searchbutton" name="searchbutton" value="Search" class="button"></td>
						</tr>
					</table>
				</form>
				<hr>
				<?php 
					require 'employee_details.php';
				?>
			</div>
			
			<div style="padding:5px">
				<button onclick="show('add_new_emp')" class="button">Add New</button>
				<?php echo $db_status; ?>
			</div>
			
			<!-- The below code is for adding the new record for Employee Details -->
			<div id="add_new_emp" style="padding:5px;display:none;">
				<hr>
				<h1 style="color:white;font-family:Sans-serif;">Add a New Employee</h1>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table>
						<tr>
							<td><label for="empcode" style="font-size:18px;font-family:Sans-serif;"><b>*Employee Code:</b></label></td>
							<td><input type="text" id="empcode" name="empcode" placeholder="Enter the Employee Code(Ex: EMP01)" style="height:20px;width:300px;"></td>
							<td style="color:red;"><?php echo $emp_code_err; ?></td>
						</tr>
						<tr>
							<td><label for="empname" style="font-size:18px;font-family:Sans-serif;"><b>*Employee Name:</b></label></td>
							<td><input type="text" id="empname" name="empname" placeholder="Enter the Employee Name" style="height:20px;width:300px;"></td>
							<td>*Use Underscores(_) if you are using spaces</td>
							<td style="color:red;"><?php echo $emp_name_err; ?></td>
						</tr>
						<tr>
							<td><label for="jobtitle" style="font-size:18px;font-family:Sans-serif;"><b>*Job Title:</b></label></td>
							<td>
								<select id="jobtitle" name="jobtitle" style="height:20px;width:300px;">
									<?php echo $dropdown_options;?>
								</select>
							</td>
							<td style="color:red;"><?php echo $job_title_err; ?></td>
						</tr>
						<tr>
							<td><label for="duration" style="font-size:18px;font-family:Sans-serif;"><b>*Duration:</b></label></td>
							<td><input type="number" id="duration" name="duration" placeholder="Ex: 3" style="height:20px;width:300px;" step="0.5"></td>
							<td style="color:red;"><?php echo $duration_err; ?></td>
						</tr>
						<tr>
							<td><input type="submit" id="addnewemp" name="addemp" value="Add" class="button"></td>
							<td><b>* - Required</b></td>
						</tr>
					</table>
				</form>
			</div>
			
			<!-- The below code is for editing the existing record for Employee Details -->
			<div id="editemp" style="padding:5px;display:none;">
				<hr>
				<h1 style="color:white;font-family:Sans-serif;">Edit the Record for <span id="editempheading"></span></h1>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table>
						<tr>
							<td><label for="editempcode" style="font-size:18px;font-family:Sans-serif;"><b>*Employee Code:</b></label></td>
							<td><input type="text" id="editempcode" name="editempcode" style="height:20px;width:300px;"></td>
							<td style="color:red;"><?php echo $edit_emp_code_err; ?></td>
						</tr>
						<tr>
							<td><label for="editempname" style="font-size:18px;font-family:Sans-serif;"><b>*Employee Name:</b></label></td>
							<td><input type="text" id="editempname" name="editempname" style="height:20px;width:300px;"></td>
							<td style="color:red;"><?php echo $edit_emp_name_err; ?></td>
						</tr>
						<tr>
							<td><label for="editjobtitle" style="font-size:18px;font-family:Sans-serif;"><b>*Job Title:</b></label></td>
							<td>
								<select id="editjobtitle" name="editjobtitle" style="height:20px;width:300px;">
									<?php echo $dropdown_options;?>
								</select>
							</td>
							<td style="color:red;"><?php echo $edit_job_title_err; ?></td>
						</tr>
						<tr>
							<td><label for="editduration" style="font-size:18px;font-family:Sans-serif;"><b>*Duration:</b></label></td>
							<td><input type="text" id="editduration" name="editduration" style="height:20px;width:300px;"></td>
							<td><input type="text" id="originalempcode" name="originalempcode" style="display:none;"></td>
							<td style="color:red;"><?php echo $edit_duration_err; ?></td>
						</tr>
						<tr>
							<td><input type="submit" id="updateemp" name="updateemp" value="Update" class="button"></td>
							<td><b>* - Required</b></td>
						</tr>
					</table>
				</form>
			</div>
			
			<!-- The below code is for delete the record when click on Delete button in Employee Details section -->
			<div id="deleteemp" style="padding:5px;display:none;">
				<hr>
				<h1 style="color:red;font-family:Sans-serif;">Are you sure to delete the below displaying employee?</h1>
				<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
					<table>
						<tr>
							<td><label for="deleteempcode" style="font-size:18px;font-family:Sans-serif;"><b>Employee Code:</b></label></td>
							<td>
								<input type="text" id="deleteempcode" name="deleteempcode" style="font-family:Sans-serif;font-size:18px;height:20px;width:300px;background-color:grey;color:black;border:none;" readonly>
							</td>
						</tr>
						<tr>
							<td><label for="deleteempname" style="font-size:18px;font-family:Sans-serif;"><b>Employee Name:</b></label></td>
							<td>
								<input type="text" id="deleteempname" name="deleteempname" style="font-family:Sans-serif;font-size:18px;height:20px;width:300px;background-color:grey;color:black;border:none;" readonly>
							</td>
						</tr>
						<tr>
							<td><label for="deletejobtitle" style="font-size:18px;font-family:Sans-serif;"><b>Job Title:</b></label></td>
							<td>
								<input type="text" id="deletejobtitle" name="deletejobtitle" style="font-family:Sans-serif;font-size:18px;height:20px;width:300px;background-color:grey;color:black;border:none;" readonly>
							</td>
						</tr>
						<tr>
							<td><label for="deleteduration" style="font-size:18px;font-family:Sans-serif;"><b>Duration:</b></label></td>
							<td>
								<input type="text" id="deleteduration" name="deleteduration" style="font-family:Sans-serif;font-size:18px;height:20px;width:300px;background-color:grey;color:black;border:none;" readonly>
							</td>
						</tr>
					</table><br>
					<input type="submit" id="confirmempdelete" name="confirmempdelete" value="YES" class="cancelbutton">
					<input type="submit" id="cancelempdelete" name="cancelempdelete" value="NO" class="button">
				</form>
			</div>
		</div>

		<!-- The below code is for implementing the logic for Calculating Payroll -->
		<div id="b3" class="containerTab" style="display:none;margin:1px;border:1px solid;">
			<?php require 'calculate_payroll.php'; ?>
			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
				<table>
					<tr>
						<td><label for="employeeselect" style="font-size:18px;font-family:Sans-serif;"><b>*Choose an Employee:</b></label></td>
						<td><select id="employeeselect" name="employeeselect" style="height:20px;width:300px;">
								<?php echo $employee_dropdown_options; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><label for="monthselect" style="font-size:18px;font-family:Sans-serif;"><b>*Month:</b></label></td>
						<td><select id="monthselect" name="monthselect" style="height:20px;width:300px;">
								<option value="January">January</option>
								<option value="February">February</option>
								<option value="March">March</option>
								<option value="April">April</option>
								<option value="May">May</option>
								<option value="June">June</option>
								<option value="July">July</option>
								<option value="August">August</option>
								<option value="September">September</option>
								<option value="October">October</option>
								<option value="November">November</option>
								<option value="December">December</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label for="yearselect" style="font-size:18px;font-family:Sans-serif;"><b>*Year:</b></label></td>
						<td><select id="yearselect" name="yearselect" style="height:20px;width:300px;">
								<?php echo $year_dropdown_options; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td><label for="hoursselect" style="font-size:18px;font-family:Sans-serif;"><b>*Total Hours:</b></label></td>
						<td><input type="text" id="hoursselect" name="hoursselect" placeholder="Total hours worked by Employee" style="height:20px;width:300px;"></td>
					</tr>
					<tr>
						<td></td>
						<td><b>* - Required</b></td>
					</tr>
				</table>
				<td><input type="submit" id="showcalculation" name="showcalculation" value="Calculate" class="button"></button></td>
				<td><a style="color:white;" href="http://localhost:8012/Payroll_Application/index.php">Reset</a></td>
			</form>
			<div id="payrollcalculation"><?php echo $output;?></div>
			<div id="reportbuttons" style="text-align:center;"><?php echo $report_buttons; ?></div>
		</div>

	</div>
</body>
</html>