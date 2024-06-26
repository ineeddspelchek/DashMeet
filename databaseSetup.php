<?php
    // Sources:
    // https://dba.stackexchange.com/questions/65289/multiple-primary-keys-in-postgresql
    // https://dba.stackexchange.com/questions/279564/how-to-set-default-value-as-empty-string-for-all-rows-at-once-in-mysql-while-cre
    // https://stackoverflow.com/questions/14182079/delete-rows-with-foreign-key-in-postgresql
    // 

    // DEBUGGING ONLY! Show all errors. TODO: REMOVE
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    // Note that these are for the local Docker container
    $host = "nope";
    $port = "nope";
    $database = "nope";
    $user = "nope";
    $password = "nope";

    $dbHandle = pg_pconnect("host=$host port=$port dbname=$database user=$user password=$password");

    if ($dbHandle) {
        echo "Success connecting to database";
    } else {
        echo "An error occurred connecting to the database";
    }

    $res  = pg_query($dbHandle, "drop sequence if exists users_seq cascade;");
    $res  = pg_query($dbHandle, "create sequence users_seq;");

    $res  = pg_query($dbHandle, "drop sequence if exists calendars_seq cascade;");
    $res  = pg_query($dbHandle, "create sequence calendars_seq;");

    $res  = pg_query($dbHandle, "drop sequence if exists meetings_seq cascade;");
    $res  = pg_query($dbHandle, "create sequence meetings_seq;");

    $res  = pg_query($dbHandle, "drop sequence if exists events_seq cascade;");
    $res  = pg_query($dbHandle, "create sequence events_seq;");


    $res  = pg_query($dbHandle, "drop table if exists ourUsers cascade;");
    $res  = pg_query($dbHandle, "drop table if exists meetings cascade;");
    $res  = pg_query($dbHandle, "drop table if exists calendars cascade;");
    $res  = pg_query($dbHandle, "drop table if exists events cascade;");

    $res  = pg_query($dbHandle, "drop table if exists membersOf cascade;");


    $res  = pg_query($dbHandle, "create table ourUsers (
        id int primary key default nextval('users_seq'),
        email text,
        fullname text,
        password text
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table meetings (
        id int primary key default nextval('meetings_seq'),
        name text,
        hostID int references ourUsers(id),
        hostJSON text default '{\"availabilities\": []}',
        start timestamp,
        stop timestamp
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table calendars (
        id int primary key default nextval('calendars_seq'),
        name text,
        userID int references ourUsers(id),
        json text
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table events (
        id int primary key default nextval('events_seq'),
        calendarID int references calendars(id) ON DELETE CASCADE,
        name text,
        start timestamp,
        stop timestamp
    );");
    assert($res !== false);

    $res  = pg_query($dbHandle, "create table membersOf (
        meetingID int references meetings(id) ON DELETE CASCADE,
        memberID int references ourUsers(id),
        json text default '{\"availabilities\": []}',
        jsonSubmitted boolean default false,
        primary key (meetingID, memberID)
    );");
    assert($res !== false);
