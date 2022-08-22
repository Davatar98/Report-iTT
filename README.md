<h1> ReportITT Documentation </h1>
This details the documentation for the website reporting system. This ReadMe documents the design and implementation of ReportITT, a crowdsourcing tool for reporting faults to local utility companies. If interested, please email me for more documentation.



<h1>Design </h1>

<h2> System Overview</h2>

The following image illustrates the overview of the system and the system components.
![image](https://user-images.githubusercontent.com/81074516/185842401-9f2f1cea-0b80-47f3-b5d3-8a2b7ea3fabe.png)

<h2>Use Case</h2>

![image](https://user-images.githubusercontent.com/81074516/185843044-d251b7fa-a21a-4aaf-a0e3-432d99d78209.png)

<h2>Database Design</h2>

![image](https://user-images.githubusercontent.com/81074516/185843225-941f6520-a706-410c-9185-8c70363ceb13.png)

<h2>Login and Registration </h2>

<h3> Login Truth Table for Assignment of Roles based on email domain </h3>
<table style="width:100%">
<caption>Table 1: Role Assignment Table </caption>
  <tr>
    <th>User Email Domain</th>
    <th>Organisation</th>
    <th>ID Assigned to User ("organisation_id" value)</th>
    
  </tr>
<tr>
   <tr><td>@ttec.co.tt</td>
   <td>TTEC/td>
    <td>1</td>
   
</tr>

  
  <tr>
    <td>@wasa.gov.tt</td>
    <td>WASA</td>
    <td>2</td>
  </tr>
  
   <tr><td>@tstt.co.tt</td>
   <td>TSTT</td>
  <td>3</td> 
    </tr>
  
   <tr><td>Other Domains</td>
   <td>PUBLIC</td>
  <td>5</td> 
    </tr>
</table>

<h3>Login/Registration Flowchart </h3>
![image](https://user-images.githubusercontent.com/81074516/185845288-ab6485dc-6406-44c3-9d8b-9c1828b98c26.png)

<h2>Reporting System Module</h2>
![image](https://user-images.githubusercontent.com/81074516/185845577-6e3320bf-ec56-461f-98b5-271989904484.png)

<h3> Report Creation </h3>
This module facilitates report creation by allowing only users of the PUB class the ability to create 
reports through a form. The form allows users to select the relevant provider from a 
dropdown which is populated directly from the database. This uses the Organisations table from 
the database. Depending on the selection, the form then displays only the issues for that service 
provider. For example, if “WASA” is selected from the “Select a Service Provider” dropdown, 
only the reportable issues such as “No Water” and “Water Leak” would be selectable. The issues 
displayed in the “Select an Issue” dropdown is populated by the Faults table. This ensures that 
only valid providers and issues are selected.Additionally, the form contains a map from which users can input the physical location of the fault.
The map contains a geolocation functionality which allows for a point to be placed on the map, 
reflecting the position of the user’s device. The coordinates of the point (placed by selection or 
geolocation) are shown in the “Latitude” and “Longitude” textboxes. Furthermore, the form also 
accepts images either from the device storage, or from the camera of capable devices. For reports 
containing images, the images are compressed to reduce the space required for the storage. The 
limit for uploaded images is set to a total of 3 images and the maximum size of each image cannot 
exceed 10MB. The accepted formats are JPG, JPEG and PNG.

<h3>Report Statuses and their Description</h3>

<table>
<caption> Table 2: Description of Report Statuses </caption>
<tr>
    <th>Status</th>
    <th>Description</th>
</tr>

<tr>
    <td>Created</td>
    <td>This is automatically assigned to reports that are newly created. This is the default 
report status.
</td>
</tr>
<tr>
    <td>Submitted</td>
    <td>This status is automatically assigned to reports that have been submitted to the 
service provider.</td>
</tr>
<tr>
    <td>In-Progress</td>
    <td>This status is manually assigned to reports by the service provider.
</td>
</tr>
<tr>
    <td>Completed</td>
    <td> This status is manually assigned to reports by the service provider. These are not 
displayed on the map</td>
</tr>
<tr>
    <td>Discarded</td>
    <td> This status is automatically assigned to reports that have been automatically 
discarded by the system. These are not displayed on the map.</td>
</tr>

</table>

<h4> Managing Report Flow </h4>
The report flow is managed automatically by the system (see above image). This module also allows for 
the users of the PUB user class to vote and flag reports. Based on the amount of votes and flags a 
report receives, the system makes the decision to change the status from “Created” to “Discarded” 
or to “Submitted”. In the latter case, a notification is sent to the relevant service provider emails 
with a link to the detailed view of the report.
When a report is created, it is displayed on the Home/Map View and users of the PUB class are 
allowed to vote and flag the report for a set time. This mechanism uses the “power of the crowd” 
to vet reports before service providers are notified. When the time elapses, the system checks the 
number of votes and flags and then decides on whether a report status is changed to discarded or 
63
to submitted, and the relevant service provider is notified. This mechanism is fully automatic and
is designed for minimal intervention by users of the SP class.
Additionally, when a report is discarded by the system, it is given the status “Discarded” and is 
soft deleted from the database i.e., the report can still be retrieved in the event it was discarded due 
to malicious activity. Regardless of the type of deletion, the marker representing the report on the 
map is removed. 
As mentioned, reports can be discarded by the system in two ways (Figure 13). If a report is 
discarded due to the condition of having five (5) or more flags, the system treats the report as 
inappropriate, and the user receives a strike. When a user has three (3) strikes, the isBanned column 
on the Users table is set to 1 (The user is banned from the website).
 <h2> Statistic Module</h2>
 
 <table>
 <caption> Table 3: Cumulative Metrics for Evaluating Utility Performance</caption>
 
 <tr>
    <th>Metric</th>
    <th>Description</th>
 
 </tr>
 
 <tr>
 <td>Average Resolution Rate Per Fault </td>
 <td>This shows the average resolution rates per fault type.</td>
 
 </tr>
  <tr>
 <td>Number of Faults Reported by Type </td>
 <td>This shows the average resolution rates per fault type.</td>
 
 </tr>
  <tr>
 <td>Average Resolve Time Per 
Fault Type </td>
 <td>This shows the average resolve time by fault type. This metric 
is calculated from when a report status is changed from 
“Submitted” to “Completed”.</td>
 
 </tr>
  <tr>
 <td>Average Acknowledgement 
Time Per Fault Type </td>
 <td>This metric is calculated from when a report status is changed 
from “Submitted” to “In-Progress”.
</td>
 
 </tr>
 </table>
 
 The metrics are chosen to show the current performance of the utility company.
Additionally, the application performs scheduled exports to each service provider with their data. 
Exports are scheduled for every week, fortnight, month, quarter, and year. Exports are sent to the 
respective service providers via email. Each email contains two attachments: an XLSX file and a 
PDF summary of the data for that time. Each XLSX document contains four sheets titled “All 
Outstanding Reports”, “All Outstanding Reports Completed this Period”, “All Reports Created 
This Period” and “All Reports Resolved This Period”. The “period” in these sheets correspond to 
the period that the export was created (week, fortnight, month, quarter, year)

<h2> Security Considerations </h2>

<h3>Role Based Access Control</h3>
Functionality of the website is restricted based on some characteristics of the 
users and the reports. This section explores some of the permissions requirements of the system.

<table>
    <br>
<caption> Table 4: Functions and Required Permissions </caption>

<tr> 
    <th> Function </th>
    <th>Permissions Required</th>
</tr>
<tr> 
    <td> Create Report </td>
    <td>Must be a PUB-class user.
</td>
</tr>
<tr> 
    <td> Update Status </td>
    <td>Must be an admin and belong to the same 
organisation as the report.</td>
</tr>
<tr> 
    <td> View Statistics </td>
    <td>Permissions Required</tFd>
</tr>
<tr> 
    <td> Function </td>
    <td>Must be an admin and belong to the same 
organisation as the report</td>
</tr>
<tr> 
    <td> View Report Details (Creator, Contact Details) </td>
    <td> Must be an admin and belong to the same 
organisation as the report.</td>
</tr>
<tr> 
    <td> Can Vote or Flag </td>
    <td>Must be a PUB-class user</td>
</tr>
</table>

The security measures implemented in this project considered six (6) common website security vulnerabilities: SQL injections, Cross Site 
Scripting (XSS), Broken Authentication and Session Management, Insecure Direct Object 
References, Security Misconfiguration, and Cross-Site Request Forgery.

<h1> Implementation Specifics </h1>
The project was completed using the incremental model, where a new feature was added on each increment. 
Tools and Software Used in Implementation
The choice of implementation tools was guided by the technology discovery in chapter 2, where 
the technology stacks of other websites and mobile applications were examined and compared. 

<h2> Development Framework </h2>
Laravel was chosen as the web development framework. This full stack framework was chosen 
based on scalability, security, ease of testing (due to the PHPUnit integration), as well as built-in 
functionality.The application was implemented in Laravel version 8 (updated to v8.83.6), using 
PHP version 8.1.1.

<h2> Database Management System</h2>
Out of the possible selections, MariaDB was chosen. MariaDB is 
compatible with MySQL at the binary level, which is also a benefit as the other local utility 
reporting systems in Trinidad and Tobago are likely to have used a MySQL database as previously 
explored. MariaDB version 10.4.22 was chosen as the database management system.
<h2>Local Development Server </h2>

For local development, XAMPP version 3.3.0 was used. This application offered the Apache web 
server version 2.4.52 and integrated both MariaDB and PHP for development. This platform was 
used to locally host and test the website. Additionally, for testing on mobile devices, ngrok was 
used to expose the local server port to the internet. Ngrok version 2.3.40 was used.

<h2>Graphical User Interface</h2>

<table>
    <caption>Table 5: Software, Tools and Libraries Used</caption>
     <tr>
        <th>Tool/Software/Library Used </th>
        <th> Purpose </th>
    </tr>
    
  <tr>
    <td>Bootstrap</td>
    <td>To create responsive website components and speed up 
development.</td>
   
  </tr>
  <tr>
    <td>LeafletJS</td>
    <td>To create responsive map container</td>
    
  </tr>
     <tr>
    <td>OSM Tiles</td>
    <td>Free map tiles</td>
    
  </tr>
     <tr>
    <td>jQuery UI and jQuery</td>
    <td>To implement functionality such as the date picker as well as to 
simplify ajax requests</td>
    
  </tr>
     <tr>
    <td>Blade Templating Engine</td>
    <td>Included in Laravel, used to pass data, and write conditional 
statements in the views</td>
    
  </tr>
</table>
   


<h3>Creating Reports Form</h3>
The “Select an Issue” dropdown in the “Create Report” from was populated based on the selection 
made from the “Select a Service Provider” dropdown. This was also achieved with AJAX 
requests. This was implemented such that only valid issues/faults can be reported to the correct 

service providers. The request used the route “'/report/create/getfaults/{providerID}” to pass the 
request to the controller, “ReportController”. The method “getFaultNames($id)” queried the Faults 
table using the ID parameter to return the list of faults.
    <h3>My Reports </h3>
*PUB-Class Users*
jQuery Datatables was used to implement this view. This is because the data tables include 
functionality such as sorting, searching and pagination. The report information is passed from the 
“UserController” controller to the view.
*SP-Class Users*
The table contained an additional jQuery component known as the jQuery date picker. This was
used as an extra filter to help service providers sort reports received more easily. The report 
information is passed from the “AdminController” controller to the view.
    <h3>Statistics</h3>
The charts for the metrics were generated using the Chartisan Laravel package. This was used as 
it reduced development time and created responsive charts. The created charts can be found in the 
charts folder. Each chart file queries the database based on the currently logged in user’s 
“organisation_id” attribute and passes the results into arrays which correspond to the X and Yaxes of the chart. The charts can be found in the Charts folder. The data is passed through an API 
created by the chartisan plugin (Chartisan 2022) for each chart created.
    <h3>My Profile</h3>
This was implemented using HTML and CSS. When a user submits this form, the request is passed 
into the “Profile Controller”. When a user submits a form, the data is validated in the “Profile 
74
Controller” and depending on the change, the corresponding table record are updated. If the phone 
and/or number property is changed, the verification status of the property is reset. The user will 
have to verify the new number or email address or both, accordingly.
    <h2>Login/Register Module </h2>
    
<h3>Phone and Email Verification</h3>
Laravel provides the login and register functionality out-of-the-box inclusive of the views. Edits 
were made to tailor it to the current application. When a user registers, an SMS as well as an email 
is sent to the user through their provided phone number and email address respectively. 
For the verification code, a random number is generated and stored in the User_Codes table, before 
being sent via the Twilio messaging service using the Twilio SDK v. 6.35. When the user enters 
the code, if the code matches, the database record, the user is allowed to continue.
Twilio messaging service was chosen as to send the SMS due to their comprehensive 
documentation and reliability. The email verification Event and Listener is also included out-ofthe-box for Laravel. This was enabled through the “RegisterController” and the MailTrap SMTP 
server was used. This was chosen as the free package was enough to cover the project usage 
requirements.
Additionally, Twilio requires the phone numbers to be of E.164 format. This was implemented as 
a cast using the Laravel Phone package (Propaganistas 2022). This package was chosen as it is 
reliable, updated, and popular, with over 8.8 million downloads.
    <h3>Organization ID Assignment</h3>
In the “RegisterController”, a substring of the email was matched with the domains in the 
Organisations table. If the domain existed, the isAdmin column is set to 1 and the user is assigned 
75
the ID of the organisation the domain corresponds to. If the domain does not exist in the 
Organisations table, the isAdmin attribute is set to 0 and the “Public” organisation ID (5) is 
assigned to the user.
    <h1> Home & Map View</h1>
This was implemented using the LeafletJS plugin as well as GitHub packages
for controlling icon colours and clustering. The map also contained controls for 
filtering of the markers by status and service provider. The map used AJAX requests to load the 
markers from the database. The AJAX request is written in the script tag of the “index.blade.php” 
file in the Reports subfolder of the Views folder and uses three routes (“/leafletTTEC”,
“/leafletTSTT”, “/leafletWASA”) to pass the requests to the controller. This request is handled in 
the ReportController functions: “getCoordinatesTTEC ()”, “getCoordinatesTSTT ()”, and 
“getCoordinatesWASA”. Separate requests were done to avoid the “freezing” of the device when 
many markers must be loaded.
These AJAX requests were used to position markers on the map as well as populate the contents 
of the marker popups.
<h2>Reporting System </h2>
    <h3>Creating Reports</h3>
When a report is created, the submitted form request is sent to the “ReportController” controller. 
The request is validated in the controller and then stored in the database.

<h3>Upvoting, Downvoting and Flagging functionality</h3>
This was implemented using Laravel Livewire. Like the “Create Report” form, the Livewire 
components perform AJAX request “under the hood”, without need for creating it in the script tags 
like before. This method was chosen as it reduced the development time.
    <h3>Handling Report Flow and Automation</h3>
As mentioned, the system discards the reports based on upvotes, downvotes and flags, and notifies 
the relevant persons. This is done through several job and event chains in Laravel. These jobs and 
events are placed into the queue and dispatched/fired accordingly. The queue driver used was the 
database, with the queue failed jobs and queue tables monitoring the queued jobs and events. This 
was excluded from the database design as it was a built-in component of the Laravel queueing 
system. One master job, “HandleReportStatus” was used to handle the entire flow of the job and 
event chain. This job was dispatched using the “ReportObserver” model observer. This observer 
is used to track when changes are made to the Report model. The “HandleReportStatus” job was 
dispatched by the “ReportObserver” with a delay of 15 minutes.
As the name implies, the job “HandleReportStatus” is also dispatched to the queue whenever the 
status of the report is updated. This includes when the report status is updated by a service provider.

Additionally, a “PhotoObserver” is used to observe the “Photo” model for changes. This monitors 
if photos are uploaded to the server and dispatches the job “CompressUploadedImage” to the 
queue. This job uses the Spatie Image Optimizer GitHub package (Spatie 2021) to perform 
compressions on the images. The packages use two compression binaries: jpegoptim (Kokkonen 
2014) and pngquant (pngquant.org n.d.). Spatie is a large Belgian company that creates websites 
using the Laravel framework. Spatie has several large open-source projects which involves 
77
developing packages for Laravel. Packages are maintained and highly reliable, especially when 
newer Laravel versions are released. The “CompressedUploadedImage” job retrieves the images 
from the storage path, performs the compression and overwrites the original file. This is done in 
the background at the expense of server resources.
<h3>Handling Malicious Users</h3>
Users whose reports receive more than 5 false flags, regardless of the number of votes, receive a 
strike in the User_Strikes table. The “HandleReportStatus” job implements this feature.
    <h3> Calculating Resolve and Acknowledgement Time</h3>
This is handled through an observer called “ReportStatusObserver” which is attached to the 
ReportStatus model. When a report is marked “In-Progress” the “ReportAcknowledgedByAdmin” 
event is fired which calculates the time in hours between when the report is “Submitted” to when 
it is “In-Progress”. When a report is marked “Completed” the “ReportResolvedByAdmin” event 
is fired which calculates the time in hours between when the report is “In-Progress” to when it is 
“Completed”. The time where the status is changed is logged by the Report_Statuses table, which 
is updated by the “HandleReportStatus” job. The calculated times are stored in the Reports table.
    <h2> Statistics</h2>
    <h3> Scheduled Exports</h3>
This was implemented by using the built-in cron job function of Laravel. Laravel allows for the 
creation of scheduled tasks which execute when a condition is met. In this case, the conditions are 
every week, fortnight, quarter, month, and year. These can be found in the Commands subfolder 
78
of the Console folder of the application. They are dispatched in the Kernel.php file. All exports 
are defined in the Exports folder.
    <h2> Security Implementation</h2>
Laravel contains several methods through which the permissions can be implemented. For better 
protection, redundancy is used. Additionally, all requests made by the user is sanitized and 
validated where necessary.
    <h3> Permissions and Middleware</h3>
Laravel provides Gates and Policies to implement permission-based access in the application. 
Restrictions are applied on the front-end as well as the back end. The front-end uses the “@can” 
directive which uses the Gates defined in the “AuthServiceProvider.php” file in the providers 
folder. Additionally, Laravel provides Policies which control user access. This is used when the 
permissions are grouped around a model. This is used especially for the updating status by the 
service provider. The system will not allow users who do not have the appropriate permissions to 
perform the action. Furthermore, middleware can also be used to deny access to a resource before 
passing the request to the controller. This is used in conjunction with the gates and policies to 
control website access.

<table>
    <caption> Table 6: Middleware and their Functions </caption>
    
   
<tr>
    <th>Middleware Name</th>
    <th>Function</th>
  </tr>
  <tr>
    <td>Auth</td>
    <td>Included in Laravel. Redirects the user to login 
page if not logged in</td>
  </tr>
  <tr>
    <td>checkAdmin</td>
    <td>Check if the user is an admin before 
proceeding to the page</td>
  </tr>
 <tr>
    <td>checkUser</td>
    <td>Check if the user is not admin before 
proceeding to the page</td>
  </tr>
  <tr>
    <td>checkIfProvider</td>
    <td>Check if the user is a Service Provider before 
proceeding to the page.</td>
  </tr>
<tr>
    <td>checkPhoneVerification</td>
    <td>Checks if the user has verified the phone 
number. Redirects to the verification page if 
not.</td>
  </tr>
  <tr>
    <td>checkBanned</td>
    <td>Checks if the user is banned before proceeding. 
Redirects user to login page with error 
message.</td>
  </tr>
    <tr>
    <td>Throttle</td>
    <td>Limits request to the server to a specific 
amount in a time frame.</td>
  </tr>
    <tr>
    <td>XSS</td>
    <td>Sanitizes Laravel data from any scripts or tags 
entered by malicious users.
</td>
  </tr>
 </table>
    
<h3>Protecting Against Common Risks </h3>
The XSS middleware was used to sanitize user inputs. To protect against XSS threats.
Laravel includes SQL injection protection if raw SQL queries were not used. All queries were 
done using the query builder and Eloquent ORM which protected against this threat. Laravel also 
includes a CSRF token which is used to protect against the CSRF attacks. The Insecure Direct 
Object Reference and broken access was protected against by using strict access controls provided 
by the gates, middleware, and policies. Finally, the broken authentication threat was reduced by 
using the built-in throttle middleware to limit requests within a time frame for logins. This provides 
some protection against brute force password hacking
