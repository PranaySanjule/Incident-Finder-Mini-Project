import os
import requests
from keyboard import is_pressed
from datetime import date

author = "Raghavan Naidu"

def post_image(path_img):   #expected argument is r'' string.
    url = 'http://localhost/dashboard/incident-finder/incident.php'  #http://localhost/api/incident.php?
    payload = {'log_date': str(date.today())}   #gives the log date
    with open(str(path_img), 'rb') as img:
        name_img= os.path.basename(path_img)    #gives the name of image
        files= {'image_name': (name_img,img,'multipart/form-data') }
        
        try:
            response = requests.request("POST", url, data=payload, files=files)
            #If no status return from incident.php the wait!
            while(response.text == ""): 
                if is_pressed("esc"):     # if "esc" key is pressed, program will stop.
                    print("\nProcess Stopped(No Keyword): 'esc' key pressed!")
                    exit(0);
            data = response.json()
            print(response.text)
            return data['status']
        except requests.ConnectionError:
            print("Not Connected to Server")
            return 'no connection'
        


if __name__ == '__main__':
    api_status = post_image(r'E:/tests/sample images/000.jpeg')
    print(api_status)