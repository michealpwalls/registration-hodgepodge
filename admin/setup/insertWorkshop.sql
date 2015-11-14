--
--     Copyright 2014-2015 Micheal P. Walls <michealpwalls@gmail.com>
-- 
--     This file is part of the International Student Registration System.
-- 
--     International Student Registration System is free software: you can
--     redistribute it and/or modify it under the terms of the GNU General
--     Public License as published by the Free Software Foundation, either
--     version 3 of the License, or (at your option) any later version.
-- 
--     International Student Registration System is distributed in the hope
--     that it will be useful, but WITHOUT ANY WARRANTY; without even the
--     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
--    PURPOSE. See the GNU General Public License for more details.
-- 
--     You should have received a copy of the GNU General Public License
--     along with International Student Registration System.
--     If not, see <http://www.gnu.org/licenses/>.
--

USE pdweek;

INSERT INTO `pdweek`.`workshops`
(`title`,
`room`,
`seats`,
`description`,
`presenter`,
`bio`,
`time`,
`userid`,
`bseats`,
`day`,
`release_date`,
`start_time`)
VALUES
('An emotional Workshop',
'A013',
'25',
'This is an emotional workshop. Bring tissue paper.',
'Emotional Presenter',
'What was the bio field for again?',
'PM',
'1',
'25',
'tue',
'2015-06-19 00:00:00',
'2:30');