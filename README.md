# GD Express (GDEX) Tracking API
- Return JSON formatted string of GDEX Tracking details
- Can be use for tracking the GDEX parcel in your own project/system
- Note: 
  - This is not the official API, this is actually just a "hack", or workaround for obtaining the tracking data.
  - This API will fetch data directly from the GDEX Tracking website, so if there are any problem with the site, this API will also affected. 
  
# ATTENTION
- This project is not working anymore. Read issue #1
  
# Created By
- Afif Zafri
- Date: 22/02/2017
- Contact: http://fb.me/afzafri

# Installation
- Drop all files into your server

# Usage
- ```http://site.com/api.php?trackingNo=CODE```
- where ```CODE``` is your parcel tracking number
- It will then return a JSON formatted string, you can parse the JSON string and do what you want with it.

# License
This library is under ```MIT license```, please look at the LICENSE file
