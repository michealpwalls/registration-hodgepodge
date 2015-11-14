<?php
/*
    Copyright 2014-2015 Micheal P. Walls <michealpwalls@gmail.com>

    This file is part of the International Student Registration System.

    International Student Registration System is free software: you can
    redistribute it and/or modify it under the terms of the GNU General
    Public License as published by the Free Software Foundation, either
    version 3 of the License, or (at your option) any later version.

    International Student Registration System is distributed in the hope
    that it will be useful, but WITHOUT ANY WARRANTY; without even the
    implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
    PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with International Student Registration System.
    If not, see <http://www.gnu.org/licenses/>.
*/
?>
            <form action="<?=$str_appURL;?>admin/index.php" method="get">
                <input type="hidden" name="action" value="edit-session">
                <input type="hidden" name="admtkn" value="<?=$adminTokenInput;?>">
                <label for="id">Select a session to edit:</label>
                <select name="id">
                    <option value="" selected>-- Sessions --</option>
                    <optgroup label="Day 1 Sessions">
                        <option value="1">Session 1</option>
                        <option value="11">Session 2</option>
                    </optgroup>
                    <optgroup label="Day 2 Sessions">
                        <option value="21">Session 1</option>
                        <option value="31">Session 2</option>
                        <option value="41">Session 3</option>
                    </optgroup>
                </select><br>
                <input type="submit" value="Continue">
            </form>