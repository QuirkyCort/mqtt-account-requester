import os
import time
import subprocess

FILE_PATH = '/var/www/mqtt/protected/accounts'
PWFILE_PATH = '/etc/mosquitto/pwfile'

DEBUG = False

def update_mosquitto():
    if DEBUG: print('update mosquitto')

    # Read file
    f = open(FILE_PATH)
    accounts = f.readlines()
    f.close()
    
    # Empty file
    f = open(FILE_PATH, 'w')
    f.close()
    
    # Create accounts
    account_added = False
    for account in accounts:
        account = account.strip()
        result = subprocess.run(
            ["/usr/bin/mosquitto_passwd", "-b", PWFILE_PATH, account, account],
            user='mosquitto',
            group='mosquitto',
            capture_output=True
        )
        account_added = True
        if DEBUG: print('result1:', result)

    # Reload mosquitto if necessary
    if account_added:
        result = subprocess.run(['/usr/bin/systemctl', 'reload', 'mosquitto'])
        if DEBUG: print('result2:', result)


# Main
last_modified = 0
while True:
    modified_time = os.path.getmtime(FILE_PATH)
    if modified_time != last_modified:
        if DEBUG: print('Modified', modified_time, last_modified)
        update_mosquitto()
        last_modified = os.path.getmtime(FILE_PATH)
    time.sleep(5)