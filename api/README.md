#Overview

The Library Traffic API is intended to provide an easy to access JSON based data source for the current traffic conditions in the Mary Idema Pew Library.

The base URL is `http://labs.library.gvsu.edu/library-traffic/api`

#Endpoints
###/spaces


Accepts: `GET`

Returns: JSON represntation of all of the individual spaces in the library

Example:

```
GET /spaces
[
    {
        "id": 1,
        "name": "Atrium Living Room",
        "description": null,
        "meta": {
        "url": "space/1"
        }
    },
    ...
    {
        "id": 16,
        "name": "4th Floor Reading Room",
        "description": null,
        "meta": {
            "url": "space/16"
        }
    }
]`
```

As you can see above, each space has a key named meta; this exposes the endpoint to retreive that specific space's traffic information.

###/space/<space_id>

Accepts: `GET`

Returns JSON representation of the speficied space's current traffic stats.

Example Reponse:
```
GET /space/1
{
    "id": 1,
    "name": "Atrium Living Room",
    "level": "1",
    "label": "A Few Students",
    "lastUpdated": "2015-03-31 09:34:01"
}
```