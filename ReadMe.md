Order Processing System
--------------------------------------------------------------------------------
Api:

TYPE: POST

URL:  '/order/new'

PARAMS: orderId: {orderId}

--------------------------------------------------------------------------------

The API flow works like this:

API Request

Fetch details against order id from table

Store the fetched details in Cache

Push the order id details to SMS Queue

Push the order id details to MAIL Queue

Push the order id details to Invoice creation Queue

Response
