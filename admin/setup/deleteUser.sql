USE pdweek;

-- Delete the user's profile
DELETE FROM users WHERE userid = '1';

-- Delete the user's attendance records
DELETE FROM fotcAttendees WHERE userid = '1';

-- Delete the user's registration records
DELETE FROM registered WHERE userid = '1';