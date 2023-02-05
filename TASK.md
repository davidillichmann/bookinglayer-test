# Bookinglayer-test

## Task

The task of the test is to correctly calculate room occupancy rates. Occupancy rates represent
the number of occupied versus vacant rooms. We need occupancy rates for a single day vs
multiple room ids and for a single month vs multiple room ids (so queries are not always against
all rooms).

## World

Download a fresh version of recent laravel.
Please create 3 models in system:

### Room (can be blocked or booked)
- id
- capacity (integer)

### Booking 
(is a room reservation, that takes 1 capacity. So room with 4 capacity can be booked 4
times for same date)
- id
- room_id
- starts_at (date)
- ends_at (date)

### Block 
(that’s not booking it’s just a indicator that room is not available then, same as booking
one block takes only 1 capacity)
- id
- room_id
- starts_at (date)
- ends_at (date)

These are base models, feel free to create more if you need them.

## How we calculate occupancies

Sum all booked dates / (sum capacity - sum blocks).

### Example
```
Rooms
Room A - capacity 6
Room B - capacity 4
Room C - capacity 2

Bookings
B1 - Room A 1 Jan to 5 Jan
B2 - Room A 1 Jan to 5 Jan
B3 - Room A 1 Jan to 5 Jan
B4 - Room B 1 Jan to 5 Jan
B5 - Room B 3 Jan to 8 Jan

Blocks
Room B 1 Jan to 10 Jan
```
### When we query for all rooms:

Occupancy rate for 2 Jan: Total capacity of all rooms is 12, total occupancy is 4
and there is 1 block on that date. So occupancy rate is 4 / (12-1) ~= 0,36

Occupancy rate for Jan (entire month): Total capacity of all rooms is 12 * 31(days in Jan) =
372, total occupancy is B1 (5) + B2 (5) + B3 (5) + B4 (5) + B5 (6) = 26 and blocks (10). So
occupancy rate is 26 / (372-10) ~= 0,07

### When we query for specific rooms like just B and C

Occupancy rate for 6 Jan: Total capacity of B and C is 6, total occupancy is 1
and there is 1 block on that date. So occupancy rate is 1 / (6-1) ~= 0,2

Occupancy rate for Jan (entire month): Total capacity of queried rooms is 6 * 31(days in Jan) =
186, total occupancy is B4 (5) + B5 (6) = 11 and blocks (10). So occupancy rate is 11 / (186-10)
~= 0,06

## Endpoints

```
GET /daily-occupancy-rates/{Y-m-d}?product_ids[]=X&room_ids[]=Y...
GET /monthly-occupancy-rates/{Y-m}?product_ids[]=X&room_ids[]=Y...
```
These endpoints should return a occupancy rate like {“occupancy_rate” : 0.2}
“room_ids” param is optional. When not provided we return occupancy rate for all rooms.
```
POST /booking
PUT /booking/{id}
```
These endpoints should create or update `booking` which will result in change of
*-occupancy-rates response.