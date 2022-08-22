<h1>Design </h1>
This details the documentation for the website reporting system. This section includes the design features.

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
 <caption> Cumulative Metrics for Evaluating Utility Performance</caption>
 
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
    <td>Permissions Required</td>
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
    <tdMust be a PUB-class user</td>
</tr>
</table>

The security measures implemented in this project considered six (6) common website security vulnerabilities: SQL injections, Cross Site 
Scripting (XSS), Broken Authentication and Session Management, Insecure Direct Object 
References, Security Misconfiguration, and Cross-Site Request Forgery.

<h1> Implementation Specifics </h1>
