<?php

    // Note that these are for the local Docker container
    $host = "localhost";
    $port = "5432";
    $database = "han5jn";
    $user = "han5jn";
    $password = "lfSPQ9jbVYGr"; 

    $dbHandle = pg_connect("host=$host port=$port dbname=$database user=$user password=$password");

    if ($dbHandle) {
        echo "Success connecting to database";
    } else {
        echo "An error occurred connecting to the database";
    }

    $res  = pg_query($dbHandle, "drop sequence if exists calendars_seq;");
    $res  = pg_query($dbHandle, "create sequence calendars_seq;");

    $res  = pg_query($dbHandle, "drop table if exists users;");
    $res  = pg_query($dbHandle, "drop table if exists calendars;");


    $res  = pg_query($dbHandle, "create table users (
            email  text primary key,
            fullname text,
            password    text
    );");

    $res  = pg_query($dbHandle, "create table calendars (
        id  int primary key default nextval('calendars_seq'),
        name text,
        useremail  text references users(email),
        json text
    );");
