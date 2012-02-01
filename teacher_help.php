<?php
include_once('header_footer/header.html');
?>
<table align='center'>
<tr><td>
<h2>Hi, Welcome To The Teacher Documentation.</h2>
<h1>Student Details</h1>
<p>"Student Details" option can be used by the Teacher to perform basic operation on the student data like, Viewing the Details of the student, Editing the details of the student.</p>

<h1>Upload Attendance</h1>
<p>"Upload Attendance" option can be used by the Teacher to upload the attendance record for a class. The required steps are as follows:
<h4>Step 1: In the first step Teacher need to specify the Time period for which he/she wants to upload the attendance along with the class for which the attendance to be uploaded.</h4>
<h4>Step 2: In the second step, Teacher needs to specify the name of the subject for which he/she wants to upload the attendance.</h4>
<h4>Step 3: In the final step, Teacher needs to fill the attendence record for the student of the selected class and then the record will be uploaded to the database.</h4></p>
<p>Teacher will be asked if he/she want to notify students about their attendence via SMS. If selected Yes, students of corresponding class will get SMS on their mobile phones about their attendance</p>

<h1>Upload Marks</h1>
<p>"Upload Marks" option can be used by the teacher to upload the following marks:
<li>Sessional Marks</li>
<li>Internal Marks</li>
<li>External Marks</li></p>
<p>When the marks are uploaded, Admin will be notified about it so that SMS can be sent to Students about their marks</p>

<h1>Upload Assignment</h1>
<p>"Upload Assignment" option can be used by the teacher to give assignment to the student. The required steps are as follows:
<h4>Step 1: In the first Step teacher need to specify the class to give the assignment.</h4>
<h4>Step 2: In the second step teacher need to select the subject for which the assignment will be given.</h4>
<h4>Step 3: In the third step teacher need to provide the assignment details.Teacher need to specify the assignment number, assignment details, date of 			submission and Accepted format. Moreover teacher can also upload the printed assignment in the form of PDF or DOC file.</h4></p>

<h1>Edit Records</h1>
<p>"Edit Records" option can be used by the teacher to edit the records related to marks and attendance of the student. Teacher can edit the following records:
<li>Marks Record
<ul>
<li>Sessional Marks</li>
<li>Internal Makrs</li>
<li>External Marks</li>
</ul></li>

<li>Attendence Record</li></p>

<h1>Get Report</h1>
<p>"Get Report" option can be used by the Teacher to Generate a report based on the class selected by the Teacher. Further Teacher can also specify the fields he/she wants to add to the report. The report will include the following details of the student:
<li>Personal Information Of Student</li>
<li>Contact Details of student</li>
<li>Previous Academic Record of Student</li></p>
<p>Following steps are required to generate the report:
<h4>Step 1: In the first step Teacher need to specify the class for which the report is to be generated.</h4>
<h4>Step 2: In the Second step Teacher can select the fields he/she wants to add to the report. Afte this the report will be generated using the details provided</h4></p>

<h1>Change Password</h1>
<p>"Change Password" option can be used by the Teacher to change his/her current password. Below are the required steps:
<h4>Step 1: In the first step Teacher need to specify his/her current password along with the new password he/she wish to set. Teacher must provide correct current password in order to set the new password.<h4>
<h4>Step 2: In  the second step after providing correct information, Teacher will be automatically Logged out of the program and he/she need to re-login with his/her new changed password.</h4></p>
<?php
include_once('header_footer/footer.php');
?>

