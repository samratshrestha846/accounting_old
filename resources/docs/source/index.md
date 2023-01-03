---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://127.0.0.1:8000/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_d76f747a5975afa97e90b71137f21659 -->
## Get a list of devices

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.1:8000/api/biotime/devices?per_page=10&page=1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.1:8000/api/biotime/devices"
);

let params = {
    "per_page": "10",
    "page": "1",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
[
    {
        "id": 1,
        "name": "Device 1",
        "serial_number": "213-234-123",
        "ip_address": "127.0.0.1:8000",
        "state": "New York",
        "last_activity": "2021-02-02 01:02:03",
        "status": true
    },
    {
        "id": 2,
        "name": "Device 2",
        "serial_number": "225-234-123",
        "ip_address": "127.0.0.1:8201",
        "state": "New York",
        "last_activity": "2021-02-02 01:02:03",
        "status": true
    }
]
```
> Example response (401):

```json
{
    "message": "unauthenticated"
}
```

### HTTP Request
`GET api/biotime/devices`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    `per_page` |  optional  | integer The perpage number.
    `page` |  optional  | integer The page number.

<!-- END_d76f747a5975afa97e90b71137f21659 -->

<!-- START_184a47ae1737bc2454af1d857ea2fcf3 -->
## Create a New Device

> Example request:

```bash
curl -X POST \
    "http://127.0.0.1:8000/api/biotime/devices" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"uuid":"001","name":"Device 1","serial_number":"202-123-232","ip_address":"127.0.0.1:8000","area":"127.0.0.1:8000","last_activity":"2021-03-03","status":true}'

```

```javascript
const url = new URL(
    "http://127.0.0.1:8000/api/biotime/devices"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "uuid": "001",
    "name": "Device 1",
    "serial_number": "202-123-232",
    "ip_address": "127.0.0.1:8000",
    "area": "127.0.0.1:8000",
    "last_activity": "2021-03-03",
    "status": true
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "error": false,
    "status": 200,
    "message": "Device created successfully",
    "data": {
        "id": 1,
        "name": "Device 1",
        "serial_number": "213-234-123",
        "ip_address": "127.0.0.1:8000",
        "state": "New York",
        "last_activity": "2021-02-02 01:02:03",
        "status": true
    }
}
```
> Example response (401):

```json
{
    "message": "unauthenticated"
}
```

### HTTP Request
`POST api/biotime/devices`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `uuid` | string |  required  | The uuid of the device.
        `name` | string |  required  | The name of the device.
        `serial_number` | string |  required  | The serial number of the device.
        `ip_address` | string |  required  | The ip address of the device.
        `area` | string |  required  | The area of the device.
        `last_activity` | string |  optional  | nullable The last activity date of the device.
        `status` | boolean |  optional  | The status of the device.
    
<!-- END_184a47ae1737bc2454af1d857ea2fcf3 -->

<!-- START_db12578f6aa8a303c83987da9ee3d4bc -->
## Update Device

> Example request:

```bash
curl -X PATCH \
    "http://127.0.0.1:8000/api/biotime/devices/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"name":"Device 1","serial_number":"202-123-232","ip_address":"127.0.0.1:8000","area":"127.0.0.1:8000","last_activity":"2021-03-03","status":true}'

```

```javascript
const url = new URL(
    "http://127.0.0.1:8000/api/biotime/devices/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "name": "Device 1",
    "serial_number": "202-123-232",
    "ip_address": "127.0.0.1:8000",
    "area": "127.0.0.1:8000",
    "last_activity": "2021-03-03",
    "status": true
}

fetch(url, {
    method: "PATCH",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "error": false,
    "status": 200,
    "message": "Device updated successfully"
}
```
> Example response (401):

```json
{
    "message": "unauthenticated"
}
```
> Example response (404):

```json
{
    "message": "No query for the model App\\Model found"
}
```

### HTTP Request
`PATCH api/biotime/devices/{device}`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `name` | string |  required  | The name of the device.
        `serial_number` | string |  required  | The serial number of the device.
        `ip_address` | string |  required  | The ip address of the device.
        `area` | string |  required  | The area of the device.
        `last_activity` | string |  required  | The last activity date of the device.
        `status` | boolean |  optional  | The status of the device.
    
<!-- END_db12578f6aa8a303c83987da9ee3d4bc -->

<!-- START_0ebe8ae51e333c7587b406f25b508ab2 -->
## Create a Staff Attendance

> Example request:

```bash
curl -X POST \
    "http://127.0.0.1:8000/api/biotime/employees/attendances" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"device_id":"1","employee_id":"001","date":"2021-03-04","in_time":"01:02:03","out_time":"02:02:03","remarks":"Early leave"}'

```

```javascript
const url = new URL(
    "http://127.0.0.1:8000/api/biotime/employees/attendances"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "device_id": "1",
    "employee_id": "001",
    "date": "2021-03-04",
    "in_time": "01:02:03",
    "out_time": "02:02:03",
    "remarks": "Early leave"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "error": false,
    "status": 201,
    "message": "Staff attendance created successfully",
    "data": {
        "id": 1,
        "staff_id": 1,
        "date": "2021-03-03",
        "in_time": "01:02:03",
        "out_time": "02:03:30",
        "remarks": "Early leave"
    }
}
```
> Example response (401):

```json
{
    "message": "unauthenticated"
}
```

### HTTP Request
`POST api/biotime/employees/attendances`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `device_id` | string |  required  | The id of the device.
        `employee_id` | string |  required  | The employee id of the employee.
        `date` | string |  required  | The date of the attendance.
        `in_time` | string |  required  | The Enter time of the employee in the office.
        `out_time` | string |  optional  | nullable The exit time of the employeee from the office.
        `remarks` | nullable |  optional  | The remarks of the attendance.
    
<!-- END_0ebe8ae51e333c7587b406f25b508ab2 -->


