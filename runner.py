import os
import time
from datetime import datetime
while True:
    try:
        print('======== \n \nTry on : ', str(datetime.now().strftime('%Y%m%d - %H:%M:%S')), '\n \n==========')
        os.system("py -3.7 main.py")
        response = os.self.conn.getresponse().read()
        print(response)
    except:
        print('\n \n -------- \n \nCrashed on : ', str(datetime.now().strftime('%Y%m%d - %H:%M:%S')), '\n \n----------')
        time.sleep(10)