##########################
#                        #
#                        #
#      Harold Zero X     #
#                        #
#                        #
##########################

# Version     : 0.61
# Last Update : 2019.07.26
# Git         : none
# Author      : Masood Vahid
# Supporter   : Hadi Rahavi

# Python Libraries
import time
import urllib.request
import urllib.error

# DB Required Library
import mysql.connector

# Other Required Library
import json
from datetime import datetime
from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as ec
from selenium.common import exceptions


# Database connection
# if Database Connection failed, every thing failed :)
# from config import LocalDB
from Harold import LocalDB
from Harold import Harold
from mysql.connector import Error

# Day Cycle
for cycle_in_days in range(1, 12):
    today = str(datetime.now().strftime('%Y%m%d'))
    try:
        db = mysql.connector.connect(host=LocalDB.host,
                                     database=LocalDB.database,
                                     user=LocalDB.user,
                                     password=LocalDB.password)
        if db.is_connected():
            print("Connected to MySQL Database service.")
            database = db.cursor()
            database.execute("select database();")
            record = database.fetchone()
            print("Your connected to - ", record)

            sql_command = "CREATE TABLE IF NOT EXISTS `" + today + "` """ \
                          "(id int(11) NOT NULL AUTO_INCREMENT, " \
                          "markets text DEFAULT NULL COMMENT 'json'," \
                          "averages text DEFAULT NULL COMMENT 'asks and bids average'," \
                          "submitted_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP," \
                          "PRIMARY KEY (id))"""
            database.execute(sql_command)
            database.close()

        # ChromeDriver
        options = webdriver.ChromeOptions()

        # CAD-BTC
        browserOne = webdriver.Chrome()
        browserOne.get("https://catalx.io/trade/CAD-BTC")
        browserOne.implicitly_wait(30)
        # CAD-ETH
        browserTwo = webdriver.Chrome()
        browserTwo.get('https://catalx.io/trade/CAD-ETH')
        browserTwo.implicitly_wait(30)

        # Using Try-Except structure to avoid collapse
        # when App doesnt load Correctly
        try:
            # K for count failed iteration
            # show in terminal last line at end of process
            k = 0
            # j is number of scraping in each Browser session
            # notice : TimeOutExceptionError may occur in long session
            # Prefer to keep it under 5 minutes
            for j in range(1, 550):

                # Just for terminal.
                # Database time submitted by MySQL automatically
                print(str(j) + ' ==> ' + str(datetime.now().strftime('%H:%M:%S')))
                Market = {"Kraken": {"BTC": {"ASKS": {}, "BIDS": {}}, "ETH": {"ASKS": {}, "BIDS": {}}},
                          "Catalx": {"BTC": {"ASKS": {}, "BIDS": {}}, "ETH": {"ASKS": {}, "BIDS": {}}}}

                #####################
                #                   #
                #    USD-XChange    #
                #                   #
                #####################
                # try:
                #     bitcad = urllib.request.urlopen('https://api.kraken.com/0/public/Depth?pair=xbtcad&count=10').read(1000)
                # except urllib.error.HTTPError as e:
                #     print('Error code: ', e.code)
                # except urllib.error.URLError as e:
                #     print('Reason: ', e.reason)
                # else:
                #     kraken = json.loads(bitcad.decode())
                #     for i in range(10):
                #         kraken_asks[i + 1] = float(kraken['result']['XXBTZCAD']['asks'][i][0])
                #         kraken_bids[i + 1] = float(kraken['result']['XXBTZCAD']['bids'][i][0])
                #         print('Kraken (' + str(i+1) + ')  BID : ' + str(kraken_bids[i + 1]) + ' |||  ASK : ' + str(kraken_asks[i + 1]))

                #####################
                #                   #
                #      KRAKEN       #
                #                   #
                #####################
                try:
                    bitcad = urllib.request.urlopen('https://api.kraken.com/0/public/Depth?pair=xbtcad&count=10').read(1000)
                    ethcad = urllib.request.urlopen('https://api.kraken.com/0/public/Depth?pair=ethcad&count=10').read(1000)
                except urllib.error.HTTPError as e:
                    print('Error code: ', e.code)
                except urllib.error.URLError as e:
                    print('Reason: ', e.reason)
                else:
                    kraken_btc = json.loads(bitcad.decode())
                    kraken_eth = json.loads(ethcad.decode())
                    for i in range(10):
                        Market['Kraken']['BTC']['ASKS'][float(kraken_btc['result']['XXBTZCAD']['asks'][i][0])] = float(kraken_btc['result']['XXBTZCAD']['asks'][i][1])
                        Market['Kraken']['BTC']['BIDS'][float(kraken_btc['result']['XXBTZCAD']['bids'][i][0])] = float(kraken_btc['result']['XXBTZCAD']['bids'][i][1])
                        Market['Kraken']['ETH']['ASKS'][float(kraken_eth['result']['XETHZCAD']['asks'][i][0])] = float(kraken_eth['result']['XETHZCAD']['asks'][i][1])
                        Market['Kraken']['ETH']['BIDS'][float(kraken_eth['result']['XETHZCAD']['bids'][i][0])] = float(kraken_eth['result']['XETHZCAD']['bids'][i][1])

                    # print(json.dumps(Market))  # For Debugging


                #####################
                #                   #
                #      CATALX       #
                #                   #
                #####################
                # Scrapping started from here
                # i Is number of elements in each page

                def fetcher(browser,order):
                    ask = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located((By.XPATH, "//tr[@class='price-level'][" + str(i) + "]//td[@class='ask-price']")))
                    if order == 'ASKS':
                        ask = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located((By.XPATH, "//tr[@class='price-level'][" + str(i) + "]//td[@class='ask-price']")))
                        ask_volume = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located((By.XPATH,"//table[contains(@class, 'asks-table')]//tr[@class='price-level'][" + str(i) + "]//td[2]")))
                        output = {1: float(ask.text), 2: float(ask_volume.text)}
                    elif order == 'BIDS':
                        bid = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located((By.XPATH, "//tr[@class='price-level'][" + str(i) + "]//td[@class='bid-price']")))
                        bid_volume = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located((By.XPATH,"//table[contains(@class, 'bids-table')]//tr[@class='price-level'][" + str(i) + "]//td[3]")))
                        output = {1: float(bid.text), 2: float(bid_volume.text)}
                    else:
                        output = {1: '', 2: ''}
                    return output

                for i in range(1, 11):
                    # Try again to fetch elements (defult = 5 time then except it)
                    cycle = 0
                    # Get BTC
                    for cylce1 in range(5):
                        try:
                            output = fetcher(browserOne, 'ASKS')
                            Market["Catalx"]["BTC"]["ASKS"][output[1]] = output[2]

                            output = fetcher(browserOne, 'BIDS')
                            Market["Catalx"]["BTC"]["BIDS"][output[1]] = output[2]

                            output = fetcher(browserTwo, 'ASKS')
                            Market["Catalx"]["ETH"]["ASKS"][output[1]] = output[2]

                            output = fetcher(browserTwo, 'BIDS')
                            Market["Catalx"]["ETH"]["BIDS"][output[1]] = output[2]

                            break
                        except exceptions.StaleElementReferenceException as e:
                            print('try', i, ':', cycle, 'Element Stale')
                            k = k + 1
                        except exceptions.TimeoutException as e:
                            print('try', i, ':', cycle, ' Element Timeout')
                            k = k + 1

                #####################
                #                   #
                #     DATABASE      #
                #     UP-DATER      #
                #                   #
                #####################
                if len(Market["Catalx"]["BTC"]["ASKS"]) < 5 or len(Market["Catalx"]["BTC"]["BIDS"]) < 5 or len(Market["Catalx"]["ETH"]["ASKS"]) < 5 or len(Market["Catalx"]["ETH"]["BIDS"]) < 5:
                    print('Minimum fetched items (5) cant retrive in this try. Catalx[BTC][ASKS]:', len(Market["Catalx"]["BTC"]["ASKS"]))
                else:
                    print('\n Fetched Catalx[BTC][ASKS]:', len(Market["Catalx"]["BTC"]["ASKS"]),
                          '\n Fetched Catalx[BTC][BIDS]:', len(Market["Catalx"]["BTC"]["BIDS"]),
                          '\n Fetched Catalx[ETH][ASKS]:', len(Market["Catalx"]["ETH"]["ASKS"]),
                          '\n Fetched Catalx[ETH][BIDS]:', len(Market["Catalx"]["ETH"]["BIDS"]))
                    # print(json.dumps(Market))     # For Debuging
                    average = Harold.averager(Market)
                    JsonMarkets = json.dumps(Market)
                    JsonAverages = json.dumps(average)
                    if not db.is_connected():
                        db = mysql.connector.connect(host=LocalDB.host,
                                                     database=LocalDB.database,
                                                     user=LocalDB.user,
                                                     password=LocalDB.password)

                    database = db.cursor()
                    sql_cat = "INSERT INTO `" + today + "` ""(markets, averages) VALUES (%s, %s)"
                    val_cat = (JsonMarkets, JsonAverages)

                    try:
                        database.execute(sql_cat, val_cat)
                        database.execute('COMMIT')
                        print('Market and averages Submited in Database')
                    except Error as e:
                        print("Error while Inserting to DB", e)
                        database.close()
                    database.close()
                    time.sleep(4)


        finally:
            browserOne.close()
            browserTwo.close()
            browserOne.quit()
            browserTwo.quit()
            print('Ignored Catalx Try : ' + str(k))

    except Error as e:
        print("Error while connecting to MySQL", e)

    finally:
        if db.is_connected():
            database.close()
            db.close()
            print("MySQL connection is closed")

    time.sleep(10)
