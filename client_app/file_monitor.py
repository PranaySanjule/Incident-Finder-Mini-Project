import os
from time import sleep
from keyboard import is_pressed

from my_api import post_image

__author__ = "Raghavan Naidu"

def monitor(watch_folder):  # folder location is put in argument & parsed for image files
    safe_files = ["test"]   #holds the safe file data.
    while True:
        # img_files: hold path of img files to be used by os.remove()
        try: 
            img_files = [os.path.abspath(os.path.join(watch_folder, p)) for p in os.listdir(watch_folder) if
                    p.endswith(('jpg', 'png', 'jpeg'))]
        except KeyboardInterrupt: 
            exit(0)
        # If success returned from API. Remove images Otherwise leave it alone!
        for f in img_files:
            safe = False
            for s in safe_files:    #checking if the file is already checked
                if str(s) == str(f):
                    safe = True
                    break
            
            if safe:
                break
                
            else: 
                #to avoid copy/paste error
                try:
                    api_status = post_image(f)
                except PermissionError:
                    sleep(1)
                    api_status = post_image(f)

                if api_status == 'success':
                    try: 
                        os.remove(f)
                        print("Removed: {0}\n".format(os.path.basename(f)))
                    except PermissionError:
                        sleep(1)   #IMPORTANT: Waits if a file is copy pasted into watch folder.
                        os.remove(f)
                        print("Removed: {0}\n".format(os.path.basename(f)))
                elif api_status == 'failure':
                    safe_files.append(f)
                    print("NOT Removed: {0}\n".format(os.path.basename(f)))
                elif api_status == 'no connection':
                    print("Exiting as user not connected to server!")
                    exit()
                else:
                    print("Unknown Status from API!")
                    exit()
                    
        #clearing the img_files list to avoid repeat entry Error
        img_files.clear()

        #closing application
        if is_pressed("esc"):     # if "esc" key is pressed, program will stop.
            print("\nProcess Stopped: 'esc' key pressed!")
            break


if __name__ == '__main__':
    monitor(r"E:\tests\watch folder")
