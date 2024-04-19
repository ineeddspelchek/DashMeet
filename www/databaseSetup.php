<?php

    // Note that these are for the local Docker container
    $host = "db";
    $port = "5432";
    $database = "example";
    $user = "localuser";
    $password = "cs4640LocalUser!"; 

    $dbHandle = pg_connect("host=$host port=$port dbname=$database user=$user password=$password");

    if ($dbHandle) {
        echo "Success connecting to database";
    } else {
        echo "An error occurred connecting to the database";
    }

    $res  = pg_query($dbHandle, "drop sequence if exists users_seq;");
    $res  = pg_query($dbHandle, "create sequence users_seq;");

    $res  = pg_query($dbHandle, "drop sequence if exists calendars_seq;");
    $res  = pg_query($dbHandle, "create sequence calendars_seq;");

    $res  = pg_query($dbHandle, "drop sequence if exists subcalendars_seq;");
    $res  = pg_query($dbHandle, "create sequence subcalendars_seq;");

    $res  = pg_query($dbHandle, "drop sequence if exists meetings_seq;");
    $res  = pg_query($dbHandle, "create sequence meetings_seq;");


    $res  = pg_query($dbHandle, "drop table if exists ourUsers;");
    $res  = pg_query($dbHandle, "drop table if exists calendars;");
    $res  = pg_query($dbHandle, "drop table if exists subcalendars;");
    $res  = pg_query($dbHandle, "drop table if exists events;");
    $res  = pg_query($dbHandle, "drop table if exists meetings;");

    $res  = pg_query($dbHandle, "drop table if exists membersOf;");

    print("------------------");

    $res  = pg_query($dbHandle, "create table ourUsers (
        id int primary key default nextval('users_seq'),
        email text,
        fullname text,
        password text
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table calendars (
        id int primary key default nextval('calendars_seq'),
        name text,
        userID int references ourUsers(id),
        json text
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table subcalendars (
        id int primary key default nextval('subcalendars_seq'),
        supercalendarID references calendars(id),
        meetingID references meetings(id)
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table events (
        id int primary key default nextval('subcalendars_seq'),
        calendarID references calendars(id),
        name text,
        repeats text,
        start timestamp,
        end timestamp
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table meetings (
        id int primary key default nextval('meetings_seq'),
        name text,
        hostID int,
        start timestamp,
        end timestamp
    );");
    assert($res !== false);


    $res  = pg_query($dbHandle, "create table membersOf (
        meetingID int primary key references meetings(id),
        memberID int primary key references users(id)
    );");
    assert($res !== false);
