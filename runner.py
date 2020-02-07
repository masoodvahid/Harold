import os
import time
from datetime import datetime
from Harold import *
while True:
    try:
        print('======== \n \nTry on : ', str(datetime.now().strftime('%Y%m%d - %H:%M:%S')), '\n \n==========')
        os.system("py main.py")
        response = os.self.conn.getresponse().read()
        print(response)
    except:
        print('\n \n -------- \n \nCrashed on : ', str(datetime.now().strftime('%Y%m%d - %H:%M:%S')), '\n \n----------')
        Harold.crash_reporter('95746722','⚠ Crypto Trigger has just shut down. Please Check Server')
        time.sleep(10)