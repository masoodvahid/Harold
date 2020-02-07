##########################
#                        #
#                        #
#      Harold Zero X     #
#                        #
#                        #
##########################

# Version     : 1.21
# Last Update : 2019.09.27
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
from Harold import *
from mysql.connector import Error

#######################
#                     #
#     APP CONFIGS     #
#                     #
#######################
delay_between_each_try = 5  # minutes
delay_between_exchange_rate_update = 10  # minutes
check_margin_period = 1800
k_to_c_alarm_margin = 0.8
c_to_k_alarm_margin = 0.8
raise_span_alarm = 1
error_report_id = '95746722'
good_margin_report_id = '108521416'

#####################
#                   #
#     MAIN LOOP     #
#                   #
#####################
db = mysql.connector.connect(host=LocalDB.host,
                             database=LocalDB.database,
                             user=LocalDB.user,
                             password=LocalDB.password)

try:
    # ChromeDriver
    options = webdriver.ChromeOptions()

    # CAD-BTC
    browserOne = webdriver.Chrome()
    browserOne.get("https://catalx.io/trade/CAD-BTC")
    browserOne.implicitly_wait(30)
    # Click on USD(  O)CAD on page
    WebDriverWait(browserOne, 10).until(
        ec.presence_of_element_located((By.XPATH, "//div[@class='slider round']"))).click()

    # CAD-ETH
    browserTwo = webdriver.Chrome()
    browserTwo.get('https://catalx.io/trade/CAD-ETH')
    browserTwo.implicitly_wait(30)
    # Click on USD(  O)CAD on page
    WebDriverWait(browserTwo, 10).until(
        ec.presence_of_element_located((By.XPATH, "//div[@class='slider round']"))).click()
    time.sleep(2)
except:
    Harold.crash_reporter(error_report_id, '💾 ChromeDriver Error')
    print('💾 ChromeDriver Error')

# Using Try-Except structure to avoid collapse
# when App doesnt load Correctly

try:
    is_first_time = True
    # Check DB
    if db.is_connected():
        print("Connected to MySQL Database service.")
        database = db.cursor()
        database.execute("select database();")
        record = database.fetchone()
        print("Your connected to - ", record)
        today = str(datetime.now().strftime('%Y%m%d'))
        startDay = str(datetime.now().strftime('%d'))
        database.execute(Harold.dbcreator(today))
        database.close()
    else:
        Harold.crash_reporter(error_report_id, '💾 DB connection Error')
        print('DB Connection Error')

    while True:
        if str(datetime.now().strftime('%d')) > startDay:
            today = str(datetime.now().strftime('%Y%m%d'))
            startDay = str(datetime.now().strftime('%d'))
            if not db.is_connected():
                db = mysql.connector.connect(host=LocalDB.host,
                                             database=LocalDB.database,
                                             user=LocalDB.user,
                                             password=LocalDB.password)
            database = db.cursor()
            database.execute(Harold.dbcreator(today))
            database.close()

        # Just for terminal.
        # Database time submitted by MySQL automatically
        print(str(datetime.now().strftime('%H:%M:%S')))
        Market = {"Kraken": {"BTC": {"ASKS": {}, "BIDS": {}}, "ETH": {"ASKS": {}, "BIDS": {}}},
                  "Catalx": {"BTC": {"ASKS": {}, "BIDS": {}}, "ETH": {"ASKS": {}, "BIDS": {}}}}

        #####################
        #                   #
        #    USD-XChange    #
        #                   #
        #####################

        if is_first_time is True or (int(datetime.now().strftime('%M')) != 0 and int(
                datetime.now().strftime('%M')) % delay_between_exchange_rate_update == 0):
            is_first_time = False
            try:
                USD = urllib.request.urlopen(
                    'https://free.currconv.com/api/v7/convert?q=USD_CAD&compact=ultra&apiKey=ab427304e1560200272b').read(
                    1000)
                usd = json.loads(USD.decode())
                exchange_rate = float(usd['USD_CAD'])
                print('USD-CAD Exchange rate = ', exchange_rate)
            except Exception as e:
                Harold.crash_reporter(error_report_id, '⚠ USD-CAD Exchange rate api has error %s' % e)

        #####################
        #                   #
        #      KRAKEN       #
        #                   #
        #####################
        try:
            bitcad = urllib.request.urlopen('https://api.kraken.com/0/public/Depth?pair=xbtcad&count=10').read(
                1000)
            ethcad = urllib.request.urlopen('https://api.kraken.com/0/public/Depth?pair=ethcad&count=10').read(
                1000)
            # except urllib.error.HTTPError as e:
            #     # print('Error code: ', e.code)
            #     Harold.crash_reporter(error_report_id, '⚠ Kraken Connection Error (HTTP Error) Occurred')
            # except urllib.error.URLError as e:
            #     # print('Reason: ', e.reason)
            #     Harold.crash_reporter(error_report_id, '⚠ Kraken Connection Error (URL Error) Occurred')
            kraken_btc = json.loads(bitcad.decode())
            kraken_eth = json.loads(ethcad.decode())
            for i in range(10):
                Market['Kraken']['BTC']['ASKS'][float(kraken_btc['result']['XXBTZCAD']['asks'][i][0])] = float(
                    kraken_btc['result']['XXBTZCAD']['asks'][i][1])
                Market['Kraken']['BTC']['BIDS'][float(kraken_btc['result']['XXBTZCAD']['bids'][i][0])] = float(
                    kraken_btc['result']['XXBTZCAD']['bids'][i][1])
                Market['Kraken']['ETH']['ASKS'][float(kraken_eth['result']['XETHZCAD']['asks'][i][0])] = float(
                    kraken_eth['result']['XETHZCAD']['asks'][i][1])
                Market['Kraken']['ETH']['BIDS'][float(kraken_eth['result']['XETHZCAD']['bids'][i][0])] = float(
                    kraken_eth['result']['XETHZCAD']['bids'][i][1])
            # print(json.dumps(Market))  # For Debugging
        except Exception as e:
            Harold.crash_reporter(error_report_id, '⚠ Kraken Connection Error %s' % e)
            print('Error : ', e)


        #####################
        #                   #
        #      CATALX       #
        #                   #
        #####################
        # Scrapping started from here
        # i Is number of elements in each page

        def fetcher(browser, order):
            if order == 'ASKS':
                ask = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located(
                    (By.XPATH, "//tr[@class='price-level'][" + str(i) + "]//td[@class='ask-price']")))
                ask_volume = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located((By.XPATH,
                                                                                               "//table[contains(@class, 'asks-table')]//tr[@class='price-level'][" + str(
                                                                                                   i) + "]//td[2]")))
                output = {1: round(float(ask.text) * exchange_rate, 3), 2: float(ask_volume.text)}
            elif order == 'BIDS':
                bid = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located(
                    (By.XPATH, "//tr[@class='price-level'][" + str(i) + "]//td[@class='bid-price']")))
                bid_volume = WebDriverWait(browser, 0.1).until(ec.presence_of_element_located((By.XPATH,
                                                                                               "//table[contains(@class, 'bids-table')]//tr[@class='price-level'][" + str(
                                                                                                   i) + "]//td[3]")))
                output = {1: round(float(bid.text) * exchange_rate, 3), 2: float(bid_volume.text)}
            else:
                output = {1: '', 2: ''}
            return output


        for i in range(1, 11):
            # Try again to fetch elements (defult = 5 time then except it)
            cycle1 = 0
            # Get BTC
            for cycle1 in range(1, 10):
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
                    print('try', i, ':', cycle1, 'Element Stale')
                    cycle1 = cycle1 + 1
                except exceptions.TimeoutException as e:
                    print('try', i, ':', cycle1, ' Element Timeout')
                    cycle1 = cycle1 + 1

        #####################
        #                   #
        #     DATABASE      #
        #     UP-DATER      #
        #                   #
        #####################
        if len(Market["Catalx"]["BTC"]["ASKS"]) < 5 or len(Market["Catalx"]["BTC"]["BIDS"]) < 5 or len(
                Market["Catalx"]["ETH"]["ASKS"]) < 5 or len(Market["Catalx"]["ETH"]["BIDS"]) < 5:
            print('Minimum fetched items (5) cant retrive in this try. Catalx[BTC][ASKS]:',
                  len(Market["Catalx"]["BTC"]["ASKS"]))
        else:
            table = PrettyTable(['', 'BTC Kraken', 'BTC Catalx', 'ETH Kraken', 'ETH Catalx'])
            table.add_row(
                ['Fetched Asks', len(Market["Kraken"]["BTC"]["ASKS"]), len(Market["Catalx"]["BTC"]["ASKS"]),
                 len(Market["Kraken"]["ETH"]["ASKS"]), len(Market["Catalx"]["ETH"]["ASKS"])])
            table.add_row(
                ['Fetched Bids', len(Market["Kraken"]["BTC"]["BIDS"]), len(Market["Catalx"]["BTC"]["BIDS"]),
                 len(Market["Kraken"]["ETH"]["BIDS"]), len(Market["Catalx"]["ETH"]["BIDS"])])
            print(table)
            # print('\n Fetched Catalx[BTC][ASKS]:', len(Market["Catalx"]["BTC"]["ASKS"]),
            #       '\n Fetched Catalx[BTC][BIDS]:', len(Market["Catalx"]["BTC"]["BIDS"]),
            #       '\n Fetched Catalx[ETH][ASKS]:', len(Market["Catalx"]["ETH"]["ASKS"]),
            #       '\n Fetched Catalx[ETH][BIDS]:', len(Market["Catalx"]["ETH"]["BIDS"]))
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
            sql_cat = "INSERT INTO `" + today + "`""(markets, averages, exchange_rate) VALUES (%s, %s, %s)"
            val_cat = (JsonMarkets, JsonAverages, exchange_rate)

            try:
                database.execute(sql_cat, val_cat)
                database.execute('COMMIT')
                # print('Market and averages Submitted in Database')
                print('✔')
            except Error as e:
                print("Error while Inserting to DB", e)
                database.close()
            database.close()

            #####################
            #                   #
            #      SENDING      #
            #      MESSAGE      #
            #                   #
            #####################
            # message to be sent

            if 'max_spans' not in vars():
                old_max_span = max(0, 0, 0, 0)
            else:
                old_max_span = max_spans

            btc_ck = Harold.spaner(average['Catalx']['BTC']['ASKS'], average['Kraken']['BTC']['BIDS'])
            btc_kc = Harold.spaner(average['Kraken']['BTC']['ASKS'], average['Catalx']['BTC']['BIDS'])
            eth_ck = Harold.spaner(average['Catalx']['ETH']['ASKS'], average['Kraken']['ETH']['BIDS'])
            eth_kc = Harold.spaner(average['Kraken']['ETH']['ASKS'], average['Catalx']['ETH']['BIDS'])
            max_spans = max(btc_ck, btc_kc, eth_ck, eth_kc)

            if 'last_message_time' not in vars() or (time.time() - last_message_time) > check_margin_period \
                    or (max_spans > 0 and old_max_span > 0 and (max_spans - old_max_span) > raise_span_alarm):
                Harold.alerter(c_to_k_alarm_margin, btc_ck, 'BTC C->K')
                Harold.alerter(k_to_c_alarm_margin, btc_kc, 'BTC K->C')
                Harold.alerter(c_to_k_alarm_margin, eth_ck, 'ETH C->K')
                Harold.alerter(k_to_c_alarm_margin, eth_kc, 'ETH K->C')
                last_message_time = time.time()
                goal = PrettyTable(['', 'BTC', 'ETH'])
                goal.add_row(['C-K', btc_ck, eth_ck])
                goal.add_row(['K-C', btc_kc, eth_kc])
                print(goal)
            time.sleep(delay_between_each_try)
finally:
    browserOne.close()
    browserTwo.close()
    browserOne.quit()
    browserTwo.quit()
    print('Im sorry for CRASH :(')
