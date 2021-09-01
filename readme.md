<h2>##TRACE</h2>

<p>TRACE or Tracking, Retrieval and Archiving of Communications for Efficiency is a web-based platform for the storage and tracking of incoming and outgoing organizational communications. It ensures that all communications are acted upon accordingly and facilitates interaction between the top management, supervisor and employee in the delegation and performance of tasks relative to the communication. TRACE also digitizes all communications thereby making it easier for staff to check and act on those assigned to them wherever and whenever. Productivity tracking-related statistics which informs the top management of the efficiency of a functional unit and employee with respect to the speed in taking action to an assigned task, is also included in TRACE.</p>

<br>
<h3>## SERVER REQUIREMENTS</h3>

- Ubuntu 14.04 or above
- PHP 7.0 or above
- Apache 2.4
- MySQL 5.7
- Composer

<br>
<h3>## INSTALLATION</h3>
1. Clone the repository via git:

		$ git clone http://repo.dostcentral.ph/bianca/trace_system.git /var/web/trace

2. Create the database

3. Import the provided database entitled trace_emtpy.sql, which can be found on the trace_system/storage/sql directory, to the newly created database.

4. Create a .env file and do necessary configurations for the database name, username, and password.
	Example:
		DB_DATABASE=trace_v3_empty
		DB_USERNAME=dost_trace
		DB_PASSWORD=dost_trace

5. Create a .htaccess file inside the trace/public directory and do necessary configurations.

6. After cloning and doing the needed configurations, try accessing the system thru your browser.

<i>Note: For instructions on how to use TRACE, please refer to the TRACE_UserManual_2.3.pdf file located within the directory.</i>

<br>
<h3>## ISSUES AND BUGS</h3>
For any encountered errors and bugs, kindly send an e-mail to Bianca Pantoja via bjrpantoja@gmail.com. Aside from that, you may declare the said issues on the Issues menu on the repository. All bugs and issues will be promptly addressed.

<br>
<h3>## RECOMMENDATIONS</h3>
Any recommendations for the improvement of the system is very much welcome. Kindly send an email to Bianca Pantoja via bjrpantoja@gmail.com to suggest the said recommendations.# trace
# trace
