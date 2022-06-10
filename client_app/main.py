import os
from time import sleep
from sys import exit
import webbrowser
from file_monitor import monitor

__author__ = "Raghavan Naidu"

print("Incident Finder")
#Inputs folder's path that needs to be watched.
watch_folder = input("Enter PATH of the Directory to be Watched: ")
try:    # exits program if user enter incorrect path twice and continues if user inputs correct 
    os.stat(watch_folder)
except (FileNotFoundError, OSError):
    try:
        watch_folder = input("Enter a correct PATH: ")
        os.stat(watch_folder)
    except (FileNotFoundError, OSError):
        print("Incorrect path, program closing...")
        exit()

#continuously running program till 'esc' key is pressed
print("\nContinuously Watching Directory: {0}".format(os.path.basename(watch_folder)))
print("To End: Press 'esc' Key\n")

#Turn on Website
sleep(2)
webbrowser.open('http://localhost/dashboard/incident-finder/index.php')

sleep(1)
monitor(watch_folder=watch_folder)

# E:\tests\watch folder  
# ^^^example path^^^  
