Web Based Stock Forecasters
Developed By:
- Mohammed Latif
- Robert Adrion
- Manoj Velagaleti
- Robin Karmakar
- Vincent Chen
- Syedur Rahman
- Peter Zheng

******************* File Structure *******************

index.php : splashscreen

admin.php : administrative page

search.php : page called upon once a search query is executed

ajax : folder containing all ajax related scripts

	/addstock.php : deals with administrator adding stocks

	/graph.php : deals with graphing various data

	/removestock.php : deals with administrator removing stock

	/searchsuggestions.php : deals with filling in search suggestions while a user types into the search bar

classes : folder containing all required php classes
	
	/controller.php : deals with communicating between objects

	/dbConnection.php : deals with communicating with the database

	/grapher.php : deals with providing data necessary for graphing

	/pagemaker.php : creates html page upon search queries

	/query.php : contains all necessary SQL statements

	/searcher.php : deals with providing search results based on keyword or suggestions

	/stockExtractor.php : extracts information from csv files and stores said data

	/stockRetriever.php : retrieves a csv file from Yahoo Finance containing necessary stock data

images : folder containing all necessary images

mysql-connector : driver required for MATLAB to communicate with MySQL

stylesheets : folder containing all stylesheets necessary for proper UI design

	/admin.css : administrative page stylesheet

	/jquery-ui.css : jquery ui related stylesheet

	/main.css : main stylesheet used across all pages

	/search.css : search results stylesheet

	/splashscreen.css : splashscreen stylesheets

js : folder containing all javascript file

	/ajax.js : deals with ajax calls

	/bars.js : animates bars on top of search bar whenever user moves the cursor

	/splashscreen.js : animates tooltips upon hover as well as startup animations

******************* Installing necessary software *******************

The system will initially require the installation of the following software on a UNIX based operating system preferably MAC OSX Mavericks v 10.9.2:
1. PHP v 5.3.5
2. MySQL v 5.5.90
3. Apache 2.0.64
4. MATLAB R2013a

For ease of use the user can opt to rather download and install MAMP, a free software for MAC OSX that will automatically install and configure all the above software, except MATLAB. MAMP is located at the following site: http://www.mamp.info/en/

******************* Configuring MATLAB *******************

Once all software have been installed and running, move the contents of the "code" folder into the root directory of the server. If using MAMP, the root directory will have the following path: /Applications/MAMP/htdocs 

MATLAB will then have to be configured to be able to connect to the MySQL database. Here is how:
1. Open ~MatlabRoot/toolbox/local/classpath.txt in a text editor of choice.

2. To the end of the file append the FULL path of the mysql-connector-java-5.1.29-bin.jar file located in the mysql-connector folder and restart MATLAB if it is currently running. 


******************* Creating Database and Tables *******************

A php script has been created to automatically create a database and tables necessary for the system to function. Open the createDB.php file with a browser of choice. If running MAMP the URL will be: localhost:*port*/createDB.php where *port* corresponds to the port which Apache is running on. (In many cases this is 8888 but the user should make sure the correct port is chosen)

******************* Viewing the System Web Interface *******************

Open the index.php file in a browser of choice to view the splashscreen. Again, if using MAMP the URL will be localhost:*port*/index.php where *port* corresponds to the port which Apache is running on. All other web pages can be navigated from this one page. 

******************* Adding stocks to be tracked *******************

Once on the splashscreem, click the login link located at the top right. This will redirect the browser to the administrative page. In the "Add Stocks" card, enter the ticker symbols for stocks that wish to be added seperated by commas and click the "Add" button. 


