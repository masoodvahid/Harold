# Harold
Crypto Trigger for Catalx and Kraken Source

# Instalation Guide 

#### 1- Install Xampp
1- Install last version of Xampp <br>
2- edit apache-httpd.conf
~~~~
servername localhost:80 --> servername SERVER_IP:80
~~~~
3- Restart Apache <br>
4- Remove all things on htdocs and past HTML package on there <br>
5- Create empty table on mySQL **_altcoin-trader_** <br>
6- Open port 80 on windows firewall by this <a href='https://docs.microsoft.com/en-us/sql/reporting-services/report-server/configure-a-firewall-for-report-server-access?view=sql-server-ver15'> Help </a>

#### 2- Install Python
1- Install python 3.x <br>
2- Install Pip <br>
3- Install this Packges :
~~~~
pip install selenium
pip install urllib3
pip install mysql-connector-python
pip install pyTelegramBotAPI
pip install PrettyTable
~~~~ 

#### 3- Install Chrome and Chrome Dirver Last versions
add chrome driver to system env <br>
~~~~
notice : check Chrome version and ChromeDriver Version are the same
~~~~

# v 0.7
published in : **_2019.08.05_**
~~~~
- Improve Code Stabality
- Check date and re run after 24 houres
- Handle some crash error
- Convert CAD to USD and give USD exchange rate from api
~~~~

# v 0.6
published in : **_2019.07.26_**
~~~~
- Add ETH to Markets
~~~~

# v 0.5
published in : **_2019.07.25_**
~~~~
- Add config file 
- Add 24h support chart
- Add bids-asks volume
- Update span equations
- Update DB structures for improve performance
<<<<<<< HEAD
~~~~
