# GD Express (GDEX) Tracking API
- Return JSON formatted string of GDEX Tracking details
- Can be use for tracking the GDEX parcel in your own project/system
- Note:
  - This is not the official API, this is actually just a "hack", or workaround for obtaining the tracking data.
  - This API will fetch data directly from the GDEX Tracking website, so if there are any problem with the site, this API will also affected.

# Created By
- Afif Zafri
- Date: 22/02/2017
- Updated At: 16/01/2020
- Contact: http://fb.me/afzafri

# Installation
```composer require afzafri/gd-express-tracking-api:dev-master```

# Usage
- ```http://site.com/api.php?trackingNo=CODE```
- where ```CODE``` is your parcel tracking number
- It will then return a JSON formatted string, you can parse the JSON string and do what you want with it.

# Sample Response
```yaml
{
  "http_code": 200,
  "error_msg": "No error",
  "message": "Record Found",
  "data": [
    {
      "date_time": "2020-09-21 17:15:47",
      "status": "Delivered",
      "location": "Kuching"
    },
    {
      "date_time": "2020-09-21 10:16:54",
      "status": "Out for delivery",
      "location": "Kuching"
    },
    {
      "date_time": "2020-09-12 10:08:34",
      "status": "Inbound to KCH station",
      "location": "Kuching"
    },
    {
      "date_time": "2020-09-09 03:07:08",
      "status": "In transit",
      "location": "Petaling Jaya"
    },
    {
      "date_time": "2020-09-09 02:55:00",
      "status": "In transit",
      "location": "Petaling Jaya"
    },
    {
      "date_time": "2020-09-08 19:03:50",
      "status": "Outbound from JHB station",
      "location": "Johor Bharu"
    },
    {
      "date_time": "2020-09-08 16:23:41",
      "status": "Picked up by courier",
      "location": "Johor Bharu"
    }
  ],
  "info": {
    "creator": "Afif Zafri (afzafri)",
    "project_page": "https://github.com/afzafri/GD-Express-Tracking-API",
    "date_updated": "16/01/2020"
  }
}
```

# License
This library is under ```MIT license```, please look at the LICENSE file
