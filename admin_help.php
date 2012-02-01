<?php
include_once('header_footer/header.html');
?>
<table align='center'>
<tr><td>
<h2>Hi, Welcome To The Admin Documentation.</h2>
<h1>Student Details</h1>
<p>"Student Details" option can be used by the Admin to perform basic operation on the student data like, Viewing the Details of the student, Editing the details of the student and deleting the record of the student from the database.</p>

<h1>Add Student</h1>
<p>"Add Student" option can be used by the admin to add the new student to the database</p>

<h1>Add Other</h1>
<p>"Add Other" option can be used by the admin to add new user to application. The following users can be added using this option:
<li>Admin</li>
<li>Teacher</li>
<li>Training And Placement</li></p>

<h1>Manage Users</h1>
<p>"Manage Users" option can be used by the admin to edit the login details for the following:
<li>Admin</li>
<li>Teacher</li>
<li>Training And Placement</li>
<li>Student</li></p>

<h1>Send SMS</h1>
<p>"Send SMS" option can be used by the admin to send following types of SMS:
<li>Admin can Send SMS to individual students using Roll No</li>
<li>Admin can Send SMS to notify students about their marks</li></p>
<p>Whenever a teacher uploads marks to the database, the admin will be notified by the SMS and when the marks are uploaded by all the teachers for a semester, then admin can send sms to students which will contain the report of marks obtained by student in corresponding test and semester</p>

<h1>Get Report</h1>
<p>"Get Report" option can be used by the admin to Generate a report based on the class selected by the admin. Further admin can also specify the fields he/she wants to add to the report. The report will include the following details of the student:
<li>Personal Information Of Student</li>
<li>Contact Details of student</li>
<li>Previous Academic Record of Student</li></p>
<p>Following steps are required to generate the report:
<h4>Step 1: In the first step admin need to specify the class for which the report is to be generated.</h4>
<h4>Step 2: In the Second step admin can select the fields he/she wants to add to the report. Afte this the report will be generated using the details provided</h4></p>

<h1>Change Password</h1>
<p>"Change Password" option can be used by the admin to change his/her current password. Below are the required steps:
<h4>Step 1: In the first step admin need to specify his/her current password along with the new password he/she wish to set. Admin must provide correct current password in order to set the new password.<h4>
<h4>Step 2: In  the second step after providing correct information, Admin will be automatically Logged out of the program and he/she need to re-login with his/her new changed password.</h4></p>
<?php
include_once('header_footer/footer.php');
?>
